<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/media', name: 'admin_media_index')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        $criteria = [];

        if ($this->isGranted('ROLE_USER') && !$this->isGranted('ROLE_ADMIN')) {
            $criteria['user'] = $this->getUser();
        }

        $medias = $this->entityManager->getRepository(Media::class)->findBy(
            $criteria,
            ['id' => 'DESC'],
            25,
            25 * ($page - 1)
        );
        if (!$this->isGranted('ROLE_ADMIN')) {
            $total = $this->entityManager->getRepository(Media::class)->count(['user' => $this->getUser()]);
        }
        else {
            $total = $this->entityManager->getRepository(Media::class)->count([]);
        }


        return $this->render('admin/media/index.html.twig', [
            'medias' => $medias,
            'total' => $total,
            'page' => $page
        ]);
    }
    #[Route('/admin/media/add', name: 'admin_media_add')]
    public function add(Request $request): RedirectResponse|Response
    {
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        if ($isAdmin) {
            $albums = $this->entityManager->getRepository(Album::class)->findAll();
            $users = $this->entityManager->getRepository(User::class)->findAll();
        } else {
            $albums = $this->entityManager->getRepository(Album::class)->findBy(['user' => $this->getUser()]);
            $users = [$this->getUser()];
        }

        $media = new Media();
        $form = $this->createForm(MediaType::class, $media, [
            'albums' => $albums,
            'users' => $users,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $selectedAlbum = $media->getAlbum();
            if (!$isAdmin && $selectedAlbum->getUser() !== $user) {
                throw $this->createAccessDeniedException('You cannot add media to an album that does not belong to you.');
            }

            $media->setPath('uploads/' . md5(uniqid('', true)) . '.' . $media->getFile()->guessExtension());
            $media->getFile()->move('uploads/', $media->getPath());
            $this->entityManager->persist($media);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_media_index');
        }

        return $this->render('admin/media/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/media/delete/{id}', name: 'admin_media_delete')]
    public function delete(int $id)
    {
        $media = $this->entityManager->getRepository(Media::class)->find($id);

        if(!$this->isGranted("ROLE_ADMIN")  && $media->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You cannot delete media to an album that does not belong to you.');
        }
        $this->entityManager->remove($media);
        $this->entityManager->flush();
        $filePath = $this->getParameter('kernel.project_dir') . '/public/' . $media->getPath();

        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }

        return $this->redirectToRoute('admin_media_index');
    }
}