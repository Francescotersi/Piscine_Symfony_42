<?php

// include ('./array2hash.php');
// $array = array(array("Pierre", "30"), array("Mary", "28"));
// print_r (array2hash($array) );

function array2hash($array) {
    $hash = array();
    foreach ($array as $item) {
        $hash[$item[1]] = $item[0];
    }
    return $hash;
}