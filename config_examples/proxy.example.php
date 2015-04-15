<?php

/*
    This is an example of how one might use the meta checker with a proxy.
*/   

stream_context_set_default(
    array(
        'http' => array(
            'proxy' => "tcp://proxy.mycompany.net:80",
            'request_fulluri' => true,
        )
    )
);

?>