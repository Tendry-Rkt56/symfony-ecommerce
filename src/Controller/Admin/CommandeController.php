<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\Details;
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

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Commande $commande)
    {
        $this->entity->remove($commande);
        $this->entity->flush();
        $this->addFlash('danger', 'Commande supprimée');
        return $this->redirectToRoute('admin.commande.index');
    }

    #[Route('/{id}-update', name: 'update', methods:['POST'])]
    public function update(Commande $commande)
    {
        $commande->setCompleted(true);
        $this->entity->flush();
        $this->addFlash('success', 'Commande mise à jour');
        return $this->redirectToRoute('admin.commande.details', ['id' => $commande->getId()]);
    }

    #[Route('/{id}', name: 'details', methods:['GET'])]
    public function details(Commande $commande)
    {
        $details = $this->entity->getRepository(Details::class)->details($commande->getId());
        return $this->render('admin/commande/details.html.twig', [
            'commande' => $commande,
            'details' => $details,
        ]);
    }
}
