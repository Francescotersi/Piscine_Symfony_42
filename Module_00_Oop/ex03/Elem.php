<?php

class Elem {
    private string $content = "";
    private string $element = "";
    private array $voidTags = ["meta", "img", "hr", "br"];

    public function __construct(string $element, string $content = "") {
        if (!$element) {
            echo "Error: error invalid arguments";
            return;
        }
        $supportedTags = ["meta","img","hr","br","html","head","body","title","h1","h2","h3","h4","h5","h6","p","span","div"];
        $this->content = $content;

        if (in_array($element, $supportedTags)) {
            $this->element = $element;
        }
        else {
            echo "Error: error invalid tag entered";
            return;
        }
    }

    public function getElement() { return $this->element; }
    public function getContent() { return $this->content; }

    public function pushElement($content) {
        if ($content instanceof Elem) {
            $this->content .= $content->getHTML();
        }
        else {
            $this->content .= $content;
        }
    }

    public function getHTML() {
        if (in_array($this->element, $this->voidTags)) {
            return "<" . $this->element . ">\n\t";
        }

        if ($this->content === "") {
            return "<" . $this->element . "></" . $this->element . ">\n";
        }

        // per indentazione bellina
        $lines = explode("\n", trim($this->content, "\n"));
        $indentedContent = "";
        
        foreach ($lines as $line) {
            if ($line !== "") {
                $indentedContent .= "\t" . $line . "\n";
            }
        }
        return "<" . $this->element . ">\n" . $indentedContent . "</" . $this->element . ">\n";
    }
}