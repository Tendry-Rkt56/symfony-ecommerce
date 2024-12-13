<?php

namespace App\Controller\Admin;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Category;
use App\Entity\Commande;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class HomeController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/dashboard', name: 'admin.dashboard')]
    public function dashboard()
    {
        $products = count($this->entity->getRepository(Product::class)->findAll());
        $categories = count($this->entity->getRepository(Category::class)->findAll());
        $clients = count($this->entity->getRepository(User::class)->findAll());
        $total = $this->entity->getRepository(Commande::class)->getLatestCommande();
        $total = $this->entity->getRepository(Commande::class)->getTotal();
        $commandes = $this->entity->getRepository(Commande::class)->getLatestCommande();
        return $this->render('admin/dashboard.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'clients' => $clients,
            'total' => $total,
            'commandes' => $commandes,
        ]);
    }
}
