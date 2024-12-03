<?php

namespace App\Controller\User;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    private function suggestionsProducts(?float $budget = null, array $products = []): ?array
    {
        
        if ($budget == null) return null;

        shuffle($products);

        $suggestions = [];
        $total = 0;

        foreach ($products as $product) {
            if ($total + $product->getPrice() <= $budget) {
                $suggestions[] = $product;
                $total += $product->getPrice();
            }
        }

        return $suggestions;
    }


    #[Route('/suggestion', name: 'user.suggestion', methods:['GET'])]
    public function suggestion(Request $request, SessionInterface $session)
    {
        $budget = $request->query->get('budget');
        $products = $this->entity->getRepository(Product::class)->findAll();
        $suggestions = $this->suggestionsProducts($budget, $products);
        $total = 0;
        if ($suggestions !== null) {
            $ids = [];
            foreach($suggestions as $product) {
                $total += $product->getPrice();
                $ids[] = $product->getId();
            }
            $session->set('suggestion', $ids);
        }
        return $this->render('user/product/suggestion.html.twig', [
            'suggestions' => $suggestions,
            'total' => $total,
            'budget' => $budget ?? 0
        ]);
    }
}
