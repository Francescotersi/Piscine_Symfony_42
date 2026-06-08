<?php

class TemplateEngine {

    public function createFile($fileName, $templateName, $parameters) {
        if ($fileName === null || $templateName === null || $parameters === null) {
            echo "Error: Missing parameters for createFile method.\n";
            return;
        }

        if (!file_exists($templateName)) {
            throw new Exception("Il file template '$templateName' non esiste.");
        }
        
        $htmlContent = file_get_contents($templateName);

        foreach($parameters as $key => $value) {
            $placeholder = "{" . $key . "}";
            $htmlContent = str_replace($placeholder, $value, $htmlContent);
        }

        if (file_put_contents($fileName . ".html", $htmlContent)) {
        	echo "File created successfully: " . $fileName . ".html\n";
        } 
        else {
        	echo "Error creating file: " . $fileName . ".html\n";
        }
    }
}