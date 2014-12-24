README
======

What is Aloha Template?
-----------------------

Aloha Template is PHP 5.5 templating engine. It's default parser uses the
[Mastermins HTML5 PHP library](https://github.com/Masterminds/html5-php). With the default implementation the engine parses
(X)HTML files and build DOM like trees of node objects.

Installation
------------

Install Aloha Template engine using [composer](http://getcomposer.org/).

To install, add `vm5/aloha-template` to your `composer.json` file:

```
{
  "require" : {
    "vm5/aloha-template": "1.*"
  },
}
```

Nodes
-----

Nodes are objects which represent main HTML entities.

- Aloha\Nodes\Doctype - represents the `<!DOCTYPE html>` HTML tag.
- Aloha\Nodes\Text - represents every string value (including whitespaces). 
It is the default object used as root of the DOM tree (returned by `Template::parse()` method).
- Aloha\Nodes\Comment - represents every `<!-- comment -->` HTML tag.
- Aloha\Nodes\Element - represents every HTML normal tag.

Every node can be overriden with your own implementations. You can use the interfaces
defined in Aloha\Nodes:

- Aloha\Nodes\DoctypeInterface
- Aloha\Nodes\TextInterface
- Aloha\Nodes\CommentInterface
- Aloha\Nodes\ElementInterface

The Aloha\Nodes\NodeInterface is common for the above interfaces and should not be
implemented alone.

Attributes
----------

Attributes are object which represent `attribute=value` and the empty `attribute`
of the HTML elements (Aloha\Nodes\Element). You can define your own.

Variables
---------

TODO


Usage
-----

index.html

```html
<!DOCTYPE html>
<html>
    <head>
        <title>My page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>Hello {name}!</div>
    </body>
</html>
```

file.php

```php
<?php

// if you use composer
require "vendor/autoload.php";

$template = new Aloha\Template();
// read HTML from a file
$template->loadFromFile('index.html');
// parse the file and build DOM like tree
// $parsed is Aloha\Nodes\Text object by default
$parsed = $template->parse();

$parsed->setVariable('name', 'World');


echo $parsed->paste();

?>
```

TODO
----

- finders by id attribute, attribute names, classes etc;
- unit tests.