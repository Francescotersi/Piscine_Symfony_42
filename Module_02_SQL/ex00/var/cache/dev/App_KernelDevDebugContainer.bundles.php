<?php

return [
    'ServicesBundle' => new \Symfony\Component\DependencyInjection\Kernel\ServicesBundle(),
    'ConsoleBundle' => new \Symfony\Component\Console\ConsoleBundle(),
    'FrameworkBundle' => new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
    'DoctrineBundle' => new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
    'DoctrineMigrationsBundle' => new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
    'MakerBundle' => new \Symfony\Bundle\MakerBundle\MakerBundle(),
    'TwigBundle' => new \Symfony\Bundle\TwigBundle\TwigBundle(),
];
