<?php


class TemplateEngine {

    public function createFile($fileName, Text $text) {
        if (!$fileName || !$text)
            echo "Error: error invalid parameter";
        else {
            $htmlContent = "<!DOCTYPE html>
<html>
	<head>
		<title>Minnie</title>
	</head>
	<body>\n\t\t";

            $textContent = $text->readData();
            foreach($textContent as $content) {
                $htmlContent .= $content . "\n";
            }

            $htmlContent .= "	</body>
</html>";

            if (file_put_contents($fileName . ".html", $htmlContent)) {
                echo "File created successfully: " . $fileName . ".html\n";
            } 
            else {
                echo "Error creating file: " . $fileName . ".html\n";
            }
        }
    }
}