<?php

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class D07Extention extends Extension {
    
    public function load(array $configs, ContainerBuilder $container): void {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('d07.number', $config['number']);
        $container->setParameter('d07.enable', $config['enable']);
    }

    public function getAlias():string {
        return 'd07';
    }

}