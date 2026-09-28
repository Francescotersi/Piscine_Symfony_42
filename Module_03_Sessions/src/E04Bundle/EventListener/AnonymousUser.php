<?php

namespace App\E04Bundle\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: KernelEvents::REQUEST)]
class AnonymousUser {

    public function __construct(private Security $security) {}

    public function __invoke(RequestEvent $event): void {
        if (!$event->isMainRequest()) {
            return;
        }

        if ($this->security->getUser() !== null) {
            return;
        }   

        $request = $event->getRequest();
        if (!$request->hasSession()) {
            return;
        }
        $session = $request->getSession();
        $now = time();

        $animalNames = ['Axolotl', 'Okapi', 'Saola', 'Aye-Aye', 'Quokka', 'Pangolin', 'Numbat', 'Fossa', 'Narwhal', 'Tapir'];

        if ($session->has('anonimous_start')) {
            $startTime = $session->get('anonimous_start');
            if (($now - $startTime) > 60) {
                $session->invalidate();
                $session->set('anonimous_start', $now);
                $randomAnimal = 'anonymous_' . $animalNames[array_rand($animalNames)];
                $session->set('anonimous_name', $randomAnimal);
                $session->set('seconds_since_last_request', null);
            }
           else {
                if ($session->has('last_request_time')) {
                    $secondsSinceLast = $now - $session->get('last_request_time');
                    $session->set('seconds_since_last_request', $secondsSinceLast);
                } else {
                    $session->set('seconds_since_last_request', null);
                }
            }
        } else {
            $session->set('anonimous_start', $now);
            $randomAnimal = 'anonymous_' . $animalNames[array_rand($animalNames)];
            $session->set('anonimous_name', $randomAnimal);
            $session->set('seconds_since_last_request', null);
        }
        $session->set('last_request_time', $now);
    }
}