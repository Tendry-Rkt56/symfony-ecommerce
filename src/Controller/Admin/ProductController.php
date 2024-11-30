<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/products', name: 'admin.products.')]
#[IsGranted('ROLE_ADMIN')]
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
        $categoryId = $request->query->get('category');
        $products = $this->entity->getRepository(Product::class)->getAll($page, $search, $categoryId);
            return $this->render('admin/product/index.html.twig', [
            'products' => $products,
            'search' => $search,
            'categories' => $this->entity->getRepository(Category::class)->findAll(),
            'categoryId' => $categoryId,
        ]);
    }

    #[Route('/create', name: 'create', methods:['GET', 'POST'])]
    public function create(Request $request, SluggerInterface $slugger)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, [
            'attr' => [
                'class' => 'forms'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugger->slug($form->get('name')->getData(), '-'))
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable());
            $this->entity->persist($product);
            $this->entity->flush();
            $this->addFlash('success', 'Nouveau produit crée');
            return $this->redirectToRoute('admin.products.index');
        }

        return $this->render('admin/product/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}-edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Product $product, Request $request, SluggerInterface $slugger)
    {
        $form = $this->createForm(ProductType::class, $product, [
            'attr' => [
                'class' => 'forms'
            ]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugger->slug($form->get('name')->getData(), '-'))
                    ->setUpdatedAt(new \DateTimeImmutable());
            $this->entity->flush();
            $this->addFlash('success', 'Produit N°'.$product->getId(). ' mis à jour');
            return $this->redirectToRoute('admin.products.index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods:['DELETE'])]
    public function delete(Product $product)
    {
        $id = $product->getId();
        $this->entity->remove($product);
        $this->entity->flush();
        $this->addFlash('danger', "Produit N°$id supprimé");
        return $this->redirectToRoute('admin.products.index');
    }

}
