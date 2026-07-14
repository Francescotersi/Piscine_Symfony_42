<?php

// include('./array2hash_sorted.php');
// $array = array(array("Pierre","30"), array("Mary","28"), array("Nelly", "22"));
// print_r ( array2hash_sorted($array) );

function array2hash_sorted($array) {
    $hash = array();
    foreach ($array as $item) {
        $hash[$item[0]] = $item[1];
    }
    krsort($hash);
    return $hash;
}