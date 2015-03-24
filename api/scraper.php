<?php

if (!file_exists( '../vendor/autoload.php' )) {
    echo '{"success":false,"general_message":"Error: Composer dependencies may not be installed."}';
    die();
}

require '../vendor/autoload.php';
if ( file_exists('../config/proxy.php') ) {
    include '../config/proxy.php';
}


function checkUrl( $url ) {
    
    if ( file_exists('../config/custom.php') ) {
        include '../config/custom.php';
    }
    
    if ($url === '') {
        return false;
    }
    
    if (function_exists('file_get_contents_with_proxy')) {    
        $file = file_get_contents_with_proxy( $url );
    } else {
        $file = file_get_contents( $url );
    }
        
    if (!$file) {
        return false;
    }    
    
    $html = new simple_html_dom();
    $html->load($file);
    
    
    if (!file_exists( '../config/schema.json' )) {
        echo '{"success":false,"general_message":"Error: There is no \'schema.json\' file in the \'config\' directory."}';
        die();
    }
    
    $schema = json_decode( file_get_contents('../config/schema.json' ), TRUE);

    foreach ($schema as $i => $item) {
        $selector = $item['selector'];
        if (isset($item['attribute'])) {
            $attribute = $item['attribute'];
        } else {
            $attribute = 'innertext';
        }
        $regex = $item['pattern'];
        
        if (substr( $selector, 0, 6 ) === "custom") {
            $customFunctionName = str_replace( 'custom:', '', $selector );
            $customFunction = $custom_functions[$customFunctionName];
            try {
                $schema[$i]['contents'] = $customFunction( $html->save() );
            } catch (Exception $e) {
                $schema[$i]['contents'] = false;
            }
            
        } else {
            // fetch contents (either from attribute, or inside text)
            $selResults = $html->find( $selector );
            if (
                ($selResults)
                    &&
                ($selResults[0]->hasAttribute( $attribute  ) )
            ){
                $contents = $selResults[0]->getAttribute( $attribute );
                $schema[$i]['contents'] = $contents;
            } else {
                $schema[$i]['contents'] = '';
            }
        
        }
                        
        // check if contents match pattern
        if (substr( $regex, 0, 6 ) === 'custom') {
            // use custom function            
            $customFunctionName = str_replace( 'custom:', '', $regex );
            $customFunction = $custom_functions[$customFunctionName];
            $schema[$i]['ok'] = $customFunction( $schema[$i]['contents'] );
            
        } else {
            // test regex
            if (preg_match($regex, null) === false ) {
                throw new Exception('Not a valid regular expression: '.$regex);
            }
            
            // use as regex
            if (preg_match($regex, $schema[$i]['contents']) === 1) {
                $schema[$i]['ok'] = true;
            } else {
                $schema[$i]['ok'] = false;
            }
        }
        
        
        
    }
    return $schema;
}

function overallCheck( $results ) {
    foreach ($results as $i => $value) {
       if ($value['ok'] === false) {
           return false;
       }
    }
    return true;
}