<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\User;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private AlbumRepository $albumRepository;

    public function __construct(EntityManagerInterface $entityManager, AlbumRepository $albumRepository)
    {
        $this->entityManager = $entityManager;
        $this->albumRepository = $albumRepository;
    }
    #[Route('/admin/album', name: 'admin_album_index')]
    public function index(): Response
    {
        if($this->isGranted('ROLE_ADMIN')) {
            $albums = $this->albumRepository->findAll();
        }
        else {
            /** @var User $user */
            $user = $this->getUser();
            $albums = $user->getAlbums();
        }

        return $this->render('admin/album/index.html.twig', ['albums' => $albums]);
    }
    #[Route('/admin/album/add', name: 'admin_album_add')]
    public function add(Request $request): RedirectResponse|Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($album);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_album_index');
        }

        return $this->render('admin/album/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/album/update/{id}', name: 'admin_album_update')]
    public function update(Request $request, int $id) : RedirectResponse|Response
    {
        $user = $this->getUser();
        $album = $this->albumRepository->find($id);
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if (!$this->isGranted('ROLE_ADMIN') && $album->getUser() !== $user) {
            throw new AccessDeniedException('Access denied.');
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_album_index');
        }

        return $this->render('admin/album/update.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/album/delete/{id}', name: 'admin_album_delete')]
    public function delete(int $id): RedirectResponse
    {
        $user = $this->getUser();
        $album = $this->albumRepository->find($id);

        if (!$this->isGranted('ROLE_ADMIN') && $album->getUser() !== $user) {
            throw new AccessDeniedException('Access denied.');
        }

        $this->entityManager->remove($album);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_album_index');
    }
}