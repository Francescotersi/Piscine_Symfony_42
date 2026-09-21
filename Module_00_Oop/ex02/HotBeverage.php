<?php

class HotBeverage {

    private int $price;
    private string $nom;
    private int $resistance;

    public function __construct(int $price, string $name, int $resistance) 
    {
        $this->price = $price;
        $this->nom = $name;
        $this->resistance = $resistance;
    }

    public function getPrice() { return $this->price; }
    public function getNom() { return $this->nom; }
    public function getResistance() { return $this->resistance; }

}