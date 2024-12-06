<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entity,
        private Security $security)
    {
        
    }

    #[Route(path: '/login', name: 'app.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin.dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app.logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app.register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'attr' => [
                'class' => 'forms d-flex align-items-center justify-content-center gap-2 flex-column'
            ]
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()))
                ->setImage($this->image($form->get('image')->getData(), $user, 'user', 'user'))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
            $this->entity->persist($user);
            $this->entity->flush();
            $this->redirectToRoute('user.products');
        }
        return $this->render('security/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/profil/update', name: 'user.profil.update', methods:['GET', 'POST'])]
    public function update(Request $request, UserPasswordHasherInterface $hasher)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form = $this->createForm(UserType::class, $user, [
            'attr' => [
                'class' => 'forms d-flex align-items-center justify-content-center gap-2 flex-column'
            ]
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()))
                ->setImage($this->image($form->get('image')->getData(), $user, 'user', 'user'))
                ->setUpdatedAt(new \DateTimeImmutable());
            $this->entity->persist($user);
            $this->entity->flush();
            if ($this->security->isGranted('ROLE_ADMIN')) return $this->redirectToRoute('admin.dashboard');
            return $this->redirectToRoute('user.products');
        }
        return $this->render('security/update.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);

    }

    private function image(?UploadedFile $file, User $user, string $directory = '', string $prefix = '')
    {
        if (!$file instanceof UploadedFile && $file == null && $user->getImage() == null) return null;
        if (!$file instanceof UploadedFile && $user->getImage() !== null) return $user->getImage();
        else {
            $this->deleteImage($user);
            $fileName = md5(uniqid($prefix)).'.'.$file->guessExtension();
            $file->move($this->getParameter('kernel.project_dir').'/public/image/'.$directory.'/',$fileName);
            return $directory.'/'.$fileName;
        }
    }

    private function deleteImage(User $user)
    {
        if ($user->getImage()) {
            $path = $this->getParameter('kernel.project_dir').'/public/image/'.$user->getImage();
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
