<?php

namespace App\Controller\User;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{

    #[Route('/panier', name: 'panier.index', methods:['GET'])]
    public function paniers(SessionInterface $session, ProductRepository $repository)
    {
        $paniers = $session->get('panier');
        $products = $repository->getProductInPanier($paniers);
        $total = $repository->getTotal(array_keys($paniers));
        return $this->render('user/product/panier.html.twig', [
            'products' => $products,
            'paniers' => $paniers,
            'total' => $total,
        ]);
    }

    #[Route('/panier/{id}', name: 'panier.add', methods: ['POST'])]
    public function add(int $id, SessionInterface $session): Response
    {
        $paniers = $session->get('panier', []);
        if (isset($paniers[$id])) $paniers[$id]++;
        else {
            $paniers[$id] = 1;
        }
        $session->set('panier', $paniers);
        return $this->redirectToRoute('user.products');
    }

    #[Route('/panier/delete/{id}', name: 'panier.delete', methods: ['DELETE'])]
    public function remove(int $id, SessionInterface $session): Response
    {
        $paniers = $session->get('panier', []);

        if (isset($paniers[$id])) {
        if ($paniers[$id] > 1) {
            $paniers[$id]--;
        } else {
            unset($paniers[$id]);
        }

        $session->set('panier', $paniers);
    }

    // Rediriger vers la route appropriée (par exemple, la page panier)
    return $this->redirectToRoute('panier.index');
}

}