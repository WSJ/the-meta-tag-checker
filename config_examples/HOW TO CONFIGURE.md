# How to use the config files

There are three files involved in customising the meta checker: `schema.json` (required), `custom.php` (optional) and `proxy.php` (optional). This folder contains examples of how they might be used.

## schema.json

This specifies what the meta checker actually checks. See Readme for more info.

## custom.php

Used to specify custom selectors and validators. See Readme for more info.

## proxy.php

Add a file called *proxy.php* to the config directory with a function called `file_get_contents_with_proxy`. This function should accept a URL as an argument, and return the HTML of the specified URL.
