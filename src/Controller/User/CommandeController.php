<?php

namespace App\Controller\User;

use App\Entity\Commande;
use App\Entity\Product;
use App\Event\DetailsEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    
    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/commande', name: 'user.commande', methods:['POST'])]
    public function commande(SessionInterface $session, EventDispatcherInterface $event)
    {
        $commande = new Commande();
        $paniers = $session->get('panier');
        $commande->setUser($this->getUser())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setTotal($this->getTotal($paniers));
        
        $this->entity->persist($commande);
        $this->entity->flush();

        $event->dispatch(new DetailsEvent($commande, $paniers));
        $this->addFlash('success', 'Votre commande a été passée');
        $session->set('panier', []);
        return $this->redirectToRoute('user.products');
    }

    private function getTotal(array $paniers = [])
    {
        $ids = array_keys($paniers);
        $products = $this->entity->getRepository(Product::class)->getProductInPanier($ids);
        $total = 0;
        foreach($products as $product) {
            $total += $product->getPrice() * $paniers[$product->getId()];
        }
        return $total;
    } 

}
