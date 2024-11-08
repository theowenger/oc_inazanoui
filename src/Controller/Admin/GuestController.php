<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\User;
use App\Form\AlbumType;
use App\Form\GuestType;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class GuestController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }
    #[Route('/admin/guests', name: 'admin_guests_index')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/guests/index.html.twig', ['users' => $users]);
    }

    #[Route('/admin/guest/add', name: 'admin_guest_add')]
    public function add(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(GuestType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_guests_index');

        }
        return $this->render('admin/guests/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/guest/update/{id}', name: 'admin_guest_update')]
    public function update(int $id, Request $request): Response
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(GuestType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $password = password_hash($plainPassword, PASSWORD_DEFAULT);
                $user->setPassword($password);
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('admin_guests_index');
        }

        return $this->render('admin/guests/update.html.twig', ['form' => $form->createView()]);
    }


    #[Route('/admin/guest/delete/{id}', name: 'admin_guest_delete')]
    public function delete(int $id)
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_guests_index');
    }

}