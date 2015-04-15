# How to use the config files

There are three files involved in customising the meta checker: `schema.json` (required), `custom.php` (optional) and `proxy.php` (optional). This folder contains examples of how they might be used.

## schema.json

This specifies what the meta checker actually checks. See Readme for more info.

## custom.php

Used to specify custom selectors and validators. See Readme for more info.

## proxy.php

Add a file called *proxy.php* to the config directory. Within the file, use [`stream_context_set_default`](http://php.net/manual/en/function.stream-context-set-default.php) to configure the proxy.
