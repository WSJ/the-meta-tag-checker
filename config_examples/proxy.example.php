<?php

/*
    This is an example of how one might use the meta checker with a proxy.
*/   

if (file_exists('../my/internal/proxy.php')) {
    include_once('../my/internal/proxy.php');
    function file_get_contents_with_proxy($url) {
        return myInternalProxy($url);
    }
} else {
    function file_get_contents_with_proxy($url){
        return file_get_contents($url);
    }
}

?>