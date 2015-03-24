<?php

// error_reporting(0);
header('Content-Type: application/json');

if (isset($_GET['url']) && $_GET['url'] !== '') {
    $url = $_GET['url'];

    require_once 'scraper.php';

    $results = checkUrl( $url );
    if ($results === false) {
        echo '{"success":false,"general_message":"Error: URL is invalid or page does not exist."}';
        return;
    }

    $toReturn = Array(
        'overall' => overallCheck($results),
        'details' => $results
    );

    echo json_encode($toReturn);

} else {
    echo '{"success":false,"general_message":"Error: No URL specified. Specify URL to be checked using ?url parameter."}';
}

    

    
    
?>