<?php

class Coffe extends HotBeverage {
    private string $description;
    private string $comment;

    public function __construct() {
        parent::__construct(5, "Coffe", 25);
        $this->description = "I am made by people for people";
        $this->comment = "I`m loved all over the planet";
    }

    public function getDescription()  { return $this->description; }
    public function getComment() { return $this->comment; }
}