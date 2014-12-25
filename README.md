README
======

What is Aloha Template?
-----------------------

Aloha Template is PHP 5.5 templating engine. It's default parser uses the
[Masterminds HTML5 PHP library](https://github.com/Masterminds/html5-php). 
With the default implementation the engine parses(X)HTML files and build DOM like 
trees of node objects.

Philosophy
----------

The main philosophy behind Aloha Template Engine, is that there is no need to 
invent new/another programming language to create (X)HTML templates. The HTML
itself is so powerful language so the only syntax in the template is HTML.

Installation
------------

Install Aloha Template engine using [composer](http://getcomposer.org/).

To install, add `vm5/aloha-template` to your `composer.json` file:

```
{
  "require" : {
    "vm5/aloha-template": "1.*"
  }
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

Nodes can be used to create more complex objects like widgets, gadgets, controllers,
partials or whatever you call them. You can create your own HTML tags with some 
behaviour. For example, you can create `Hyperlink` class which can render an `<a>` 
tag and also the parser can instance all `<a>` tags as `Hyperlink` class for you in PHP.
This can be done by mapping your `Hyperlink` class with the `Template::addTagMapping($tag, $class)`
method.

Attributes
----------

Attributes are object which represent `attribute=value` and the empty `attribute`
of the HTML elements (Aloha\Nodes\Element). You can define your own.

Variables
---------

Template variables are defined within single curly braces - `{` and `}`. This is the
only non-HTML syntax in the Aloha Template Engine. If you don't assign any value 
to a variable, it will be replaced with empty string. Also variables support nesting/chaining
by using dots between the different variables (`{variable.subvariable.subsubvariable}`)
Values can be either multidimensional arrays, also chaining objects with public 
properties.
Example: `{variable}` can be assigned within PHP by `NodeInterface::setVariable($variable, $value)`
method where `$variable` is the name between the curly braces and the `$value` can be
either simple variable, either complex  associative array or object. This can be 
useful if you pass whole objects or arrays to the template. 
For example: `$user` is an associative array which is result of database selection
and the whole array can be passed to the `NodeInterface::setVariable('user', $user)`. 
In the template you can define `{user.name}`, `{user.email}`, `{user.phone}` etc. 
with no need to pass every variable alone to the template engine.


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
require 'vendor/autoload.php';

$template = new Aloha\Template();
// read HTML from a file
$template->loadFromFile('index.html');
// parse the file and build DOM like tree
// $parsed is Aloha\Nodes\Text object by default
$parsed = $template->parse();

$parsed->setVariable('name', 'World');

echo $parsed->paste();

```

TODO
----

- node finders by id attribute, attribute names, classes etc;
- documentation examples;
- a caching mechanism of the parsed filed (file cache, array cache, memcache, redis, etc);
- Symfony Template Engine implementation; 
- unit tests.