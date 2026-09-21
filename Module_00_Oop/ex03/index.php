<?php

include('./Elem.php');
include('./TemplateEngine.php');

try {
    $elem = new Elem('html');
    $body = new Elem('body');
    $body->pushElement(new Elem('p', 'Lorem ipsum'));
    $elem->pushElement($body);

    $template = new TemplateEngine($elem);
    $template->createFile("meow");
}
catch (Exception $e) {
    echo ''. $e->getMessage() .'';
}