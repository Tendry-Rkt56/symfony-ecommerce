<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
            'count' => $this->entity->getRepository(Product::class)->productsLength(),
        ]);
    }

    #[Route('/create', name: 'create', methods:['GET', 'POST'])]
    public function create(Request $request, SluggerInterface $slugger)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, [
            'attr' => [
                'class' => 'forms d-flex align-items-center justify-content-center gap-2 flex-column'
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugger->slug($form->get('name')->getData(), '-'))
                    ->setImage($this->checkImage($form->get('image')->getData(), $product, 'products', 'products'))
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
                'class' => 'forms d-flex align-items-center justify-content-center gap-2 flex-column'
            ]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($slugger->slug($form->get('name')->getData(), '-'))
                    ->setImage($this->checkImage($form->get('image')->getData(), $product, 'products', 'products'))
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

    private function checkImage(?UploadedFile $file, mixed $object, string $directory = '', string $prefix = ''): ?string
    {
        if (!$file instanceof UploadedFile && $file == null && $object->getImage() == null) return null;
        if (!$file instanceof UploadedFile && $object->getImage() !== null) return $object->getImage();
        else {
            $this->deleteImage($object);
            $fileName = md5(uniqid($prefix)).'.'.$file->guessExtension();
            $file->move($this->getParameter('kernel.project_dir').'/public/image/'.$directory.'/',$fileName);
            return $directory.'/'.$fileName;
        }
    }

    private function deleteImage(mixed $object)
    {
        if ($object->getImage()) {
            $path = $this->getParameter('kernel.project_dir').'/public/image/'.$object->getImage();
            if (file_exists($path)) {
                unlink($path);
            }
        } 
    }

    #[Route('/delete/{id}', name: 'delete', methods:['DELETE'])]
    public function delete(Product $product)
    {
        $this->deleteImage($product);
        $id = $product->getId();
        $this->entity->remove($product);
        $this->entity->flush();
        $this->addFlash('danger', "Produit N°$id supprimé");
        return $this->redirectToRoute('admin.products.index');
    }

}
