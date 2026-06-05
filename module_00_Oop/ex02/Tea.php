<?php

class Tea extends HotBeverage {
    private string $description;
    private string $comment;

    public function __construct() {
        parent::__construct(3, 'Tea', 10);
        $this->description = "I was made by the British for the British";
        $this->comment = "I am consumed mostly in the UK";
    }

    public function getDescription() { return $this->description; }
    public function getComment() { return $this->comment; }

}