<?php

class Text {

    private $text = [];

    public function __construct($strings) {
        $this->text = $strings;
    }

    public function getText() { return $this->text; }

    public function append($string) {
        if ($string)
            $this->text[] .= $string;
        else
            echo "Error: error while appending string to array";
    }

    public function readData() {
        $htmlData = [];
        foreach($this->text as $key => $value) {
            $htmlData[] = "<p>" . $value . "</p>";
        }
        return $htmlData;
    }
}   