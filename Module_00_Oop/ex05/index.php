<?php

include_once('./Elem.php');
include_once('./TemplateEngine.php');
include_once('./MyException.php');

try {
    $htmlTest1 = new Elem('html');
    
    $body = new Elem('body');
    $head = new Elem('head');
    
    $htmlTest1->pushElement($body); 
    $htmlTest1->pushElement($head);

    echo "Rule 1 (HTML contains head then body): " . (!$htmlTest1->validPage() ? "Success" : "Failure") . "\n";
} catch (Exception $e) {
    echo "Rule 1 (HTML contains head then body): Success (Exception: " . $e->getMessage() . ")\n";
}

try {
    $htmlTest2 = new Elem('html');
    
    $head = new Elem('head');
    $head->pushElement(new Elem('title', 'First Title'));
    $head->pushElement(new Elem('title', 'Second Title'));
    $head->pushElement(new Elem('meta', '', ['charset' => 'UTF-8']));
    
    $body = new Elem('body');
    
    $htmlTest2->pushElement($head);
    $htmlTest2->pushElement($body);

    echo "Rule 2 (Head single title & meta): " . (!$htmlTest2->validPage() ? "Success" : "Failure") . "\n";
} catch (Exception $e) {
    echo "Rule 2 (Head single title & meta): Success (Exception: " . $e->getMessage() . ")\n";
}

try {
    $htmlTest3 = new Elem('html');
    
    $head = new Elem('head');
    $head->pushElement(new Elem('title', 'Valid Title'));
    $head->pushElement(new Elem('meta', '', ['charset' => 'UTF-8']));
    
    $body = new Elem('body');
    $p = new Elem('p', 'Questo è del testo in un paragrafo.');
    $p->pushElement(new Elem('span', 'testo extra'));
    
    $body->pushElement($p);
    
    $htmlTest3->pushElement($head);
    $htmlTest3->pushElement($body);

    echo "Rule 3 (P tag contains only text): " . (!$htmlTest3->validPage() ? "Success" : "Failure") . "\n";
} catch (Exception $e) {
    echo "Rule 3 (P tag contains only text): Success (Exception: " . $e->getMessage() . ")\n";
}

try {
    $htmlTest4 = new Elem('html');
    
    $head = new Elem('head');
    $head->pushElement(new Elem('title', 'Valid Title'));
    $head->pushElement(new Elem('meta', '', ['charset' => 'UTF-8']));
    
    $body = new Elem('body');
    $table = new Elem('table');
    $tr = new Elem('tr');
    $tr->pushElement(new Elem('div', 'Not a table cell'));
    
    $table->pushElement($tr);
    $body->pushElement($table);
    
    $htmlTest4->pushElement($head);
    $htmlTest4->pushElement($body);

    echo "Rule 4 (Table > tr > th/td): " . (!$htmlTest4->validPage() ? "Success" : "Failure") . "\n";
} catch (Exception $e) {
    echo "Rule 4 (Table > tr > th/td): Success (Exception: " . $e->getMessage() . ")\n";
}

try {
    $htmlTest5 = new Elem('html');
    
    $head = new Elem('head');
    $head->pushElement(new Elem('title', 'Valid Title'));
    $head->pushElement(new Elem('meta', '', ['charset' => 'UTF-8']));
    
    $body = new Elem('body');
    $ul = new Elem('ul');
    $ul->pushElement(new Elem('p', 'Not a list item'));
    
    $body->pushElement($ul);
    
    $htmlTest5->pushElement($head);
    $htmlTest5->pushElement($body);

    echo "Rule 5 (UL/OL only contain LI): " . (!$htmlTest5->validPage() ? "Success" : "Failure") . "\n";
} catch (Exception $e) {
    echo "Rule 5 (UL/OL only contain LI): Success (Exception: " . $e->getMessage() . ")\n";
}