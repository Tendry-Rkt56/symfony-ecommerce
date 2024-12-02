<?php

namespace App\Controller\User;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/', name: 'user.products')]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $search = $request->query->get('search', '');
        $categoryId = $request->query->get('category');
        $products = $this->entity->getRepository(Product::class)->getAll($page, $search, $categoryId);
            return $this->render('user/product/index.html.twig', [
            'products' => $products,
            'search' => $search,
            'categories' => $this->entity->getRepository(Category::class)->findAll(),
            'categoryId' => $categoryId,
        ]);
    }

    #[Route('/panier', name: 'user.panier', methods:['GET'])]
    public function paniers()
    {
        return $this->render('user/product/panier.html.twig');
    }
}
