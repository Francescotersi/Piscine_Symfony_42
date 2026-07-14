<?php

// include('./search_by_states.php');
// $results = search_by_states("Oregon, trenton, Topeka, NewJersey");
// foreach ($results as $result)
// {
//     echo $result . "\n";
// }

function search_by_states($string) {
    
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

    $hash = [];
    $hash = strtok($string, ",");
    $result = [];
    $keyState = "";

    while ($hash !== false) {
        $cleanString = trim($hash);
        if (isset($states[$cleanString])) {
            if (isset($capitals[$states[$cleanString]])) {
                $result[] = $capitals[$states[$cleanString]]. " is the capital of " . $cleanString;
            }
        }
        else {
            $keyCap = array_search($cleanString, $capitals);
            if ($keyCap !== false) {
                $keyState = array_search($keyCap, $states);
                if ($keyState !== false) {
                    $result[] = $cleanString . " is the capital of " . $keyState;
                }
                else
                    $result[] = $cleanString . " is neither a capital nor a state";
            }
            else
                $result[] = $cleanString . " is neither a capital nor a state";
        }
        $hash = strtok(",");
    }
    return $result;
}
