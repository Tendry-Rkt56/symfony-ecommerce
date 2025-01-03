<?php

namespace App\EventListener;

use App\Entity\Details;
use App\Entity\Product;
use App\Event\DetailsEvent;
use App\Event\SuggestionEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class DetailsListener
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[AsEventListener(event: DetailsEvent::class)]
    public function onDetailsEvent(DetailsEvent $event): void
    {
        foreach($event->products as $id => $nombre) {
            $detail = (new Details())   
                ->setCommande($event->commande)
                ->setProduct($this->entity->getRepository(Product::class)->find($id))
                ->setNombre($nombre);
            $this->entity->persist($detail);
        }
        $this->entity->flush();
    }

    #[AsEventListener(event: SuggestionEvent::class)]
    public function onSuggestionEvent(SuggestionEvent $event)
    {
        foreach($event->suggestions as $suggestion) {
            $detail = (new Details())   
                ->setCommande($event->commande)
                ->setProduct($this->entity->getRepository(Product::class)->find($suggestion))
                ->setNombre(1);
            $this->entity->persist($detail);
        }
        $this->entity->flush();
    }
}
