<?php

include('./TemplateEngine.php');
include('./Coffee.php');
include('./Tea.php');

$coffee = new Coffe();
$tea = new Tea();

try {
    $templateEngine = new TemplateEngine();
    $templateEngine->createFile($coffee);
    $templateEngine->createFile($tea);
} catch (Exception $e) {
    echo ''. $e->getMessage() .'';
}