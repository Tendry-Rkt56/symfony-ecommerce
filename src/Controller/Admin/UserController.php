<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user', name: 'admin.user.')]
class UserController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
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
}
