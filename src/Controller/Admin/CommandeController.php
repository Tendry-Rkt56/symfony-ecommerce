<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/commande', name: 'admin.commande.')]
class CommandeController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/', name: 'index', methods:['GET'])]
    public function index(): Response
    {
        $commandes = $this->entity->getRepository(Commande::class)->findAll();
        return $this->render('admin/commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }
}
