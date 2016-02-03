# The Meta Tag Checker.

A customisable validator for web page [meta tags](http://en.wikipedia.org/wiki/Meta_element), including [Facebook open graph tags](http://ogp.me) and [Twitter card tags](https://dev.twitter.com/cards/getting-started). Having correct meta tags can improve SEO and clickthroughs on social networks.

**[Live demo here](https://the-meta-tag-checker.herokuapp.com/)**.

## Features

- Check tags exist and have valid contents.
- Results can be accessed programmatically via API.
- Easy to customise which tags are checked and their validation rules.

## Getting started

1. Download [this repository](/archive/master.zip).
2. `composer install` (If you don't have Composer installed, [installation instructions are here](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).
3. Upload to your web host, in any directory.
4. Optional: Customise the JSON schema (see below for further instructions).


## Results API

In addition to the main results page, you can get a JSON representation of the results by adding `/api/` to the URL.

## Customising the validator schema

The meta tags and their rules are specified in `config/schema.json`.

Key | Example value | Description
--- | --- | ---
selector | link[rel='image_src'] | CSS selector for meta tag.
attribute | href | (optional) Tag property to get content from. Defaults to inner text.
pattern | \/(^http:\\/\\/)\/ | Regex (regular expression) which validates content of tag. Returns 'ok' if it finds a match. Make sure to escape slashes (so it's valid JSON) and leave out the global 'g'.
hint | URL of a promo image. | Human-readable description of the pattern.
group | meta | (optional) Used to sort tags into groups.
longname | Teaser image | Descriptive name for humans.
type | image | (optional) Set to 'image' if result should be a JPG/PNG/GIF. Other options: 'url', 'strict-url' (see below for more details).
url | https://dev.twitter.com/cards/types | (optional) URL with further information about meta tag.

### Example regex rules

If you're customising the schema, you'll need to write your rules using [regular expressions](http://en.wikipedia.org/wiki/Regular_expression) (regexes). The meta tags's contents are considered 'ok' if the regex finds a match.

Below are a few examples to get you started. For writing your own rules, [Regexr](http://regexr.com) is a useful resource.

Regex | Escaped regex | Description
--- | --- | ---
`/./` | `\/.\/` | Matches non-blank values.
`/[^(replace this)]/` | `\/[^(replace this)]\/` | Matches values which do not contain `replace this`.
`/(^http)/` | `\/(^http)\/` | Matches values beginning with `http`.
`/(^http).*(_NS_)[^\s]*/` | `\/(^http).*(_NS_)[^\\s]*\/` | Matches values beginning with `http` and containing the string `_NS_`.

### Custom selectors

Need to find something in the document by something other than a CSS selector? Simply add a new function to the `$custom_functions` array in `custom.php`. You can specify it using a selector `custom:yourFunctionName`.

### Custom validators

Need to validate a value with something more complex than a regular experession? As with custom validators, simply add a new function to the `$custom_functions` array in `$custom.php`. You can specify it using a validator `custom:yourFunctionName`.

#### Example custom validator

This function checks that an image is square.

```php
$custom_functions = Array(
    'squareImage' => function($image_url) {
        // use PHP's builtin getimagesize function to get height and width of image
        $size = getimagesize( $image_url);
        $w = $size[0];
        $h = $size[1];
        // divide to get ratio
        $ratio = $w/$h;
        // return false if not square
        if ($ratio !== 1) {
            return false;
        }
        return true;
    },
    // ... more functions here ...
);
```

Then in `schema.json`, specify the function's name in the *pattern* field of your item:

```json
{
    "selector": "link[rel='image_src']",
    "attribute": "href",
    "longname": "Promo image",
    "type": "image",
    "pattern": "custom:squareImage",
    "hint": "Should be a URL of a square image."
}
```

## Content types

- 'image': Renders content on frontend in <img> tag.
- 'url': Checks header of URL to make sure link is valid, and renders in <a> tag.
- 'strict-url': Same as 'url', but rejects redirect headers (eg. 301s). Useful for canonical URLs.

## Running behind a proxy

Add a file called *proxy.php* to the config directory. Within the file, use [`stream_context_set_default`](http://php.net/manual/en/function.stream-context-set-default.php) to configure the proxy.

## Changelog

### 2.0.1

- Shorter timeout when checking URL validity

### 2.0.0

- Simplified proxy configuration

### 1.0.1

- New content types: 'url' and 'strict-url'

### 1.0.0

- Initial release



