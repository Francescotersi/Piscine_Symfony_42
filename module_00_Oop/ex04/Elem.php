<?php

class Elem {
    private string $content = "";
    private string $element = "";
    private array $attributes = [];
    private array $supportedTags = [
        "meta", "img", "hr", "br", "html", "head", "body", "title",
        "h1", "h2", "h3", "h4", "h5", "h6",
        "p", "span", "div",
        "table", "tr", "th", "td",
        "ul", "ol", "li"
    ];
    private array $voidTags = ["meta", "img", "hr", "br"];

    public function __construct(string $element, string $content = "", array $attributes = []) {
        if (!$element) {
            echo "Error: error invalid arguments";
            return;
        }
        $this->content = $content;
        $this->attributes = $attributes;

        if (in_array($element, $this->supportedTags)) {
            $this->element = $element;
        }
        else {
            throw new MyException();
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

    public function getAttributesHtml() {
        $attributesHtml = "";
        foreach ($this->attributes as $key => $value) {
            $attributesHtml .= " " . $key . '="' . $value . '"';
        }
        return $attributesHtml;
    }

    public function getHTML() {
        if (in_array($this->element, $this->voidTags)) {
            return "<" . $this->element . $this->getAttributesHtml() .">\n\t";
        }
        // else {
        //     return "<" . $this->element . ">\n\t";
        // }

        if ($this->content === "") {
            return "<" . $this->element . $this->getAttributesHtml() . "></" . $this->element . ">\n";
        }

        // per indentazione bellina
        $lines = explode("\n", trim($this->content, "\n"));
        $indentedContent = "";
        
        foreach ($lines as $line) {
            if ($line !== "") {
                $indentedContent .= "\t" . $line . "\n";
            }
        }
        return "<" . $this->element . $this->getAttributesHtml() . ">\n" . $indentedContent . "</" . $this->element . ">\n";
    }
}