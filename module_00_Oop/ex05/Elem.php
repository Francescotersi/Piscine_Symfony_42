<?php

class Elem {
    private string $content = "";
    private string $element = "";
    private array $attributes = [];
    private array $children = [];
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
        if ($content !== "") {
            $this->children[] = $content; 
        }
        $this->attributes = $attributes;

        if (in_array($element, $this->supportedTags)) {
            $this->element = $element;
        }
        else {
            throw new MyException();
        }
    }

    public function getElement() { return $this->element; }
    public function getContent() { return $this->content; }
    public function getAttributes() { return $this->attributes; }

    public function pushElement($content) {
        $this->children[] = $content;
    }

    public function getAttributesHtml() {
        $attributesHtml = "";
        foreach ($this->attributes as $key => $value) {
            $attributesHtml .= " " . $key . '="' . $value . '"';
        }
        return $attributesHtml;
    }

    public function getHTML() {
        $this->content = "";
        foreach ($this->children as $child) {
            if ($child instanceof Elem) {
                $this->content .= $child->getHTML();
            }
            else {
                $this->content .= $child;
            }
        }

        if (in_array($this->element, $this->voidTags)) {
            return "<" . $this->element . $this->getAttributesHtml() .">\n\t";
        }

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

    // $elementChildren contine solo i figli che sono istanze di Elem, escludendo eventuale testo libero
    public function validPage(): bool {
        if ($this->element !== "html") {
            return false;
        }
        $elementChildren = array_values(array_filter($this->children, fn($c) => $c instanceof Elem));

        if (count($elementChildren) !== 2) {
            return false;
        }
        if ($elementChildren[0]->getElement() !== "head") {
            return false;
        }
        if ($elementChildren[1]->getElement() !== "body") {
            return false;
        }

        return $this->validateTree();
    }

    private function validateTree(): bool {
        $elementChildren = array_filter($this->children, fn($c) => $c instanceof Elem);

        switch ($this->element) {
            
            case "head":
                if (count($elementChildren) !== 2) return false;
                
                $hasTitle = false;
                $hasMetaCharset = false;
                
                foreach ($elementChildren as $child) {
                    if ($child->getElement() === "title") $hasTitle = true;
                    if ($child->getElement() === "meta" && array_key_exists("charset", $child->getAttributes())) {
                        $hasMetaCharset = true;
                    }
                }
                if (!$hasTitle || !$hasMetaCharset) return false;
                break;

            case "p":
                if (count($elementChildren) > 0) return false;
                break;

            case "table":
                foreach ($this->children as $child) {
                    if (is_string($child) && trim($child) !== "") return false;
                    if ($child instanceof Elem && $child->getElement() !== "tr") return false;
                }
                break;

            case "tr":
                foreach ($this->children as $child) {
                    if (is_string($child) && trim($child) !== "") return false;
                    if ($child instanceof Elem && !in_array($child->getElement(), ["th", "td"])) return false;
                }
                break;

            case "ul":
            case "ol":
                foreach ($this->children as $child) {
                    if (is_string($child) && trim($child) !== "") return false;
                    if ($child instanceof Elem && $child->getElement() !== "li") return false;
                }
                break;
        }

        foreach ($elementChildren as $child) {
            if (!$child->validateTree()) {
                return false;
            }
        }

        return true;
    }
}