<?php

include('./Elem.php');
include('./TemplateEngine.php');
include('./MyException.php');

try {
    $elem = new Elem('html');
    $body = new Elem('body');
    $body->pushElement(new Elem('p', 'Lorem ipsum', ['class' => 'text-muted']));
    $elem->pushElement($body);
    $templateEngine = new TemplateEngine($elem);
    $templateEngine->createFile("Donald");
    $elem = new Elem('undefined');
}
catch (Exception $e) {
    echo '' . $e->getMessage() . '';
}