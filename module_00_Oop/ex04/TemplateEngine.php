<?php

class TemplateEngine {

    private string $template;

    public function __construct(Elem $elem) {
        $this->template = $elem->getHTML();
    }

    public function getTemplate() { return $this->template; }

    public function createFile($fileName) {
        if (file_put_contents($fileName . ".html", $this->getTemplate())) {
        	echo "File created successfully: " . $fileName . ".html\n";
        } 
        else {
        	echo "Error creating file: " . $fileName . ".html\n";
        }
    }
}