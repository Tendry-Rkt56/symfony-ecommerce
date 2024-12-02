<?php

namespace App\EventListener;

use App\Entity\Details;
use App\Event\DetailsEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class DetailsListener
{

    #[AsEventListener(event: DetailsEvent::class)]
    public function onDetailsEvent(DetailsEvent $event, EntityManagerInterface $entity): void
    {
        
    }
}
