<?php

// include('./capital_city_from.php');
// echo capital_city_from('Oregon');
// echo capital_city_from('Origan');


function capital_city_from($state) {

    $states = [
    'Oregon' => 'OR',
    'Alabama' => 'AL',
    'New Jersey' => 'NJ',
    'Colorado' => 'CO',
    ];

    $capitals = [
    'OR' => 'Salem',
    'AL' => 'Montgomery',
    'NJ' => 'trenton',
    'KS' => 'Topeka'
    ];

    if (isset($states[$state]))
        return $capitals[$states[$state]] . "\n";
    else
       return "Unknown\n";
}