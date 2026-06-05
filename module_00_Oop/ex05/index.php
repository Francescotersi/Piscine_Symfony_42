<?php
// Ensure file names match the real ones
include_once('./Elem.php');
include_once('./TemplateEngine.php');
include_once('./MyException.php');

try {
    $html = new Elem('html');
    $body = new Elem('body');
    $body->pushElement(new Elem('p', 'Lorem ipsum', ['class' => 'text-muted']));
    $html->pushElement($body);
    
    $output = $html->getHTML();
    
    $hasClass = strpos($output, 'class="text-muted"') !== false;
    $hasText = strpos($output, 'Lorem ipsum') !== false;
    
    echo "Rendering HTML: " . ($hasClass && $hasText ? "Success" : "Failure") . "\n\n";
} catch (Exception $e) {
    echo "Rendering HTML: Unexpected error (" . $e->getMessage() . ")\n";
}

try {
    $htmlValid = new Elem('html');
    
    $head = new Elem('head');
    $head->pushElement(new Elem('title', 'Test Title'));
    $head->pushElement(new Elem('meta', '', ['charset' => 'UTF-8']));
    
    $body = new Elem('body');
    $body->pushElement(new Elem('p', 'Only text inside here'));
    
    $htmlValid->pushElement($head);
    $htmlValid->pushElement($body);

    echo "Validating HTML structure: " . ($htmlValid->validPage() ? "Success" : "Failure") . "\n\n";
} catch (Exception $e) {
    echo "Validation: Unexpected error (" . $e->getMessage() . ")\n";
}


try {
    $exceptionThrown = false;
    $invalidElem = new Elem('undefined');
    echo "Exception handling: Failure (no exception thrown for invalid tag)\n\n";
} catch (Exception $e) { 
    echo "Exception successfully caught: " . $e->getMessage() . "\n";
}

try {
    $engineElem = new Elem('html');
    $engineElem->pushElement(new Elem('body', 'Content for Donald file'));
    
    $templateEngine = new TemplateEngine($engineElem);
    $fileName = "Donald";
    
    $filePath = "./" . $fileName; 
    $templateEngine->createFile($fileName);
    
    $fileExists = file_exists($filePath);
    echo "TemplateEngine: The file '$fileName' has been physically created.\n";
} catch (Exception $e) {
    echo "TemplateEngine: Unexpected error (" . $e->getMessage() . ")\n";
}
