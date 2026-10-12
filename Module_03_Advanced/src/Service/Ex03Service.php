<?php

namespace App\Service;

class Ex03Service {

    public function uppercaseWords(string $text): string{
        return ucwords($text);
    }

    public function countNumbers(string $text): int {
        return preg_match_all("/[0-9]/", $text);
    }
}