<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ex03Controller extends AbstractController {

    #[Route(path:'/e03', name:'ex03_main')]
    public function index(#[Autowire('%e03.number_of_colors%')] int $numberOfColors): Response {

        $shadesData = [];
        $step = $numberOfColors > 1 ? 80 / ($numberOfColors - 1): 0;

        for ($i = 0; $i < $numberOfColors; $i++) {
            $lightness = 10 + ($i * $step);
            
            $shadesData[] = [
                'black' => "hsl(0, 0%, {$lightness}%)",
                'red'   => "hsl(0, 100%, {$lightness}%)",
                'blue'  => "hsl(240, 100%, {$lightness}%)",
                'green' => "hsl(120, 100%, {$lightness}%)",
            ];
        }
        return $this->render('ex03/index.html.twig', [
            'shades_data'=> $shadesData
        ]);
    }
}