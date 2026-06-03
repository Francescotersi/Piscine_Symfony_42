<?php

include("./TemplateEngine.php");

$parameters = [
    'nom' => 'Minnie',
    'auteur' =>'The Duck King',
    'description' => 'Donald is the king of everything',
    'prix' => 'I don`t know what this means'
];

try {
    $templateEngine = new TemplateEngine();
    $templateEngine->createFile("Mickey", "book_description.html", $parameters);
} catch(Exception $e) {
    echo "Exception catched: " . $e->getMessage() . "\n";
}