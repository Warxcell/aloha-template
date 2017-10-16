<?php
// if you use composer
require '../vendor/autoload.php';

$template = new Aloha\Template();
// read HTML from a file
$template->loadFromFile('test1.html');
// parse the file and build DOM like tree
// $parsed is Aloha\Nodes\Text object by default
$parsed = $template->parse();

$parsed->setVariable('name', 'World');

//print_r($parsed);

//echo $parsed->paste();

echo($template->compile());