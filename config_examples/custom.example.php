<?php

/*
This file is used to store custom functions for finding things on the page and for validating tag contents.
Add your custom functions to the array called $custom_functions, then specify them in your schema using `custom:myFunctionName`.
For further help, see the Readme file.
*/
    
$custom_functions = Array(
    'omnitureCheck' => function($html) {
        
        function fetchTextBetweenStrings($string,$beginning,$end){
            $start = strpos($string, $beginning) + strlen($beginning);
            $first = substr($string, $start);
            $end = strpos($first, $end);
            if ($start === strlen($beginning)) { return ''; }
            return substr($first, 0, $end);
        }
        
        $id = fetchTextBetweenStrings($html, 'var proj_id = "', '";');
        $name = fetchTextBetweenStrings($html, 'var proj_headline = "', '";');
        $result = "WSJ_infogrfx_interactive_".$id."_".$name;
        return $result;
        
    },
    'fbImageDimensions' => function($contents) {
        if (!function_exists('getimagesizefromstring')) {
            function getimagesizefromstring($data)
            {
                $uri = 'data://application/octet-stream;base64,' . base64_encode($data);
                return getimagesize($uri);
            }
        }
        
        if ($contents === '') {
            return false;
        }
                
        // 1,200 x 627
        if (function_exists('file_get_contents_with_proxy')) {    
            $file = file_get_contents_with_proxy( $contents );
        } else {
            $file = file_get_contents( $contents );
        }
        $size = getimagesizefromstring( $file);
        $w = $size[0];
        $h = $size[1];
        if ($w < 1200) {
            return false;
        }
        if ($h < 627) {
            return false;
        }
        $ratio = $w/$h;
        if ($ratio !== (1200/627)) {
            return false;
        }
        return true;
    }
);
    
?>