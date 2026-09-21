<?php

include('./Text.php');
include('./TemplateEngine.php');

$parameters = ['Minnie', 'The Duck King', 'Donald is the king of everything', 'I don`t know what this means'];

$text = new Text($parameters);
$text->append("Winnie The Pooh");
$text->append("Goofy");
$templateEngine = new TemplateEngine();

$templateEngine->createFile("meow", $text);
