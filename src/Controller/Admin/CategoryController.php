<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category', name: 'admin.category.')]
class CategoryController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/', name: 'index', methods:['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $search = $request->query->get('search', '');
        $categories = $this->entity->getRepository(Category::class)->getAll($page, $search);
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    #[Route('/new', name: 'create', methods:['GET', 'POST'])]
    public function create(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category, [
            'attr' => [
                'class' => 'forms'
            ]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTimeImmutable());
            $this->entity->persist($category);
            $this->entity->flush();
            $this->addFlash('success', 'Nouvelle catégorie créée');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}-edit', name: 'edit', methods:['GET', 'POST'])]
    public function edit(Category $category, Request $request)
    {
        $form = $this->createForm(CategoryType::class, $category, [
            'attr' => [
                'class' => 'forms',
            ]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUpdatedAt(new \DateTimeImmutable());
            $this->entity->flush();
            $this->addFlash('success', 'Catégorie N°'.$category->getId(). ' mis à jour');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);

    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Category $category)
    {
        $id = $category->getId();
        $this->entity->remove($category);
        $this->entity->flush();
        $this->addFlash('danger', 'Categorie N° '.$id.' mis à jour');
        return $this->redirectToRoute('admin.category.index');
    }
}
