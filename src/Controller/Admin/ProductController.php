<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/products', name: 'admin.products.')]
class ProductController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $search = $request->query->get('search', '');
        $products = $this->entity->getRepository(Product::class)->getAll($page, $search);
        return $this->render('admin/product/index.html.twig', [
            'products' => $products,
            'search' => $search,
        ]);
    }
}
