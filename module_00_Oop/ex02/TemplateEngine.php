<?php

include('./HotBeverage.php');

class TemplateEngine {

    public function createFile(HotBeverage $text) {
        $templateName = "template.html";

        if (!file_exists($templateName)) {
            throw new Exception("Il file template '$templateName' non esiste.");
        }
        
        $htmlContent = file_get_contents($templateName);

        $reflection = new ReflectionClass($text);

        $className = $reflection->getShortName();
        $fileName = $className;
        $properties = [];
        $currentReflection = $reflection;

        while ($currentReflection != null) {
            foreach ($currentReflection->getProperties() as $propriety) {
                $properties[$propriety->getName()] = $propriety;
            }
            $currentReflection = $currentReflection->getParentClass();
        }

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $getterName = 'get' . ucfirst($propertyName);
            if (method_exists($text, $getterName)) {
                $value = $text->$getterName();
                $htmlContent = str_replace("{" . $propertyName . "}", $value, $htmlContent);
            }
        }

        if (file_put_contents($fileName . ".html", $htmlContent)) {
        	echo "File created successfully: " . $fileName . ".html\n";
        } 
        else {
        	echo "Error creating file: " . $fileName . ".html\n";
        }
    }
}