<?php

$chars = str_split(file_get_contents("ex01.txt"));

foreach ($chars as $char) {
    if ($char == ',')
        echo "\n";
    else
        echo $char;
}
