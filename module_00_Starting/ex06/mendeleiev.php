<?php

$fileName = "ex06.txt";

if (!file_exists($fileName)) {
	echo "File not found: $fileName\n";
	exit(1);
}

$element = fopen($fileName, "r");
if (!$element) {
	echo "Unable to open file: $fileName\n";
	exit(1);
}

$PeriodicTable = [];
$currentColumn = 0;

while ($element && !feof($element)) {
	$line = fgets($element);
	if ($line) {
		$data = explode(",", trim($line));
		$namePosition = explode("=", $data[0]);
		$position = (int)preg_replace('/[^0-9]/', '', $namePosition[1]);
		$number = preg_replace('/[^0-9]/', '', $data[1]);
		$small = trim($data[2]);
		$molar = trim($data[3]);
		$electron = trim($data[4]);

		if ($position == 0) {
			$currentColumn++;
		}

		$PeriodicTable[$currentColumn][$position + 1] = [
			"name" => trim($namePosition[0]),
			"number" => $number,
			"small" => $small,
			"molar" => $molar,
			"electron" => $electron
		];
	}
}

$htmlContent = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Mendeleiev</title>
    <style>
        table { border-collapse: collapse; font-family: sans-serif; }
        td { border: 1px solid #333; width: 80px; height: 90px; padding: 5px; vertical-align: top; font-size: 11px; }
        td.empty { border: none; }
        h4 { margin: 0 0 5px 0; color: #2c3e50; }
        ul { margin: 0; padding-left: 15px; }
    </style>
</head>
<body>
    <h1>Mendeleev Periodic Table</h1>
    <table>
";

for ($i = 1; $i <= 7; $i++) {
    $htmlContent .= "       <tr>\n";
    for ($j = 1; $j <= 18; $j++) { 
        if (isset($PeriodicTable[$i][$j])) {
            $elementData = $PeriodicTable[$i][$j];
            $htmlContent .= "           <td>\n";
            $htmlContent .= "               <h4>" . $elementData['name'] . "</h4>\n";
            $htmlContent .= "               <ul>\n";
            $htmlContent .= "                   <li>" . $elementData['small'] . " (" . $elementData['number'] . ")</li>\n";
            $htmlContent .= "                   <li>Mass: " . $elementData['molar'] . "</li>\n";
            $htmlContent .= "                   <li>Electron: " . $elementData['electron'] . "</li>\n";
            $htmlContent .= "               </ul>\n";
            $htmlContent .= "           </td>\n";
        }
        else {
            $htmlContent .= "           <td class='empty'></td>\n"; 
        }
    }
    
    $htmlContent .= "       </tr>\n";
}

$htmlContent .= " </table>
</body>
</html>";

fclose($element);

if (file_put_contents('mendeleiev.html', $htmlContent) !== false) {
        echo "File created successfully: mendeleiev.html\n";
    } 
    else {
        echo "Error creating file: mendeleiev.html\n";
    }
