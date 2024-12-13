<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user', name: 'admin.user.')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/{id}-{slug}', name:'profil', methods:['GET'])]
    public function show(User $user)
    {
        return $this->render('admin/user/profil.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $search = $request->query->get('search', '');
        $users = $this->entity->getRepository(User::class)->getAll($page, $search);
        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user)
    {
        $id = $user->getId();
        $this->entity->remove($user);
        $this->entity->flush();
        $this->addFlash('danger', "Client N° $id supprimé");
        return $this->redirectToRoute('admin.user.index');
    }
}
