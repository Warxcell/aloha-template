<?php
// if you use composer
require '../../vendor/autoload.php';

use \Aloha\Widgets\IncludeElement;

$template = new Aloha\Template();
// read HTML from a file

$template->addTagMapping(IncludeElement::TAG_NAME, '\Aloha\Widgets\IncludeElement');

$template->loadFromFile('include.html');
// parse the file and build DOM like tree
// $parsed is Aloha\Nodes\Text object by default
$parsed = $template->parse();

$parsed->setVariable('name', 'World');

//print_r($parsed);

//echo $parsed->paste();

echo($parsed->compile());