<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;


    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('front/home.html.twig');
    }

    #[Route('/guests', name: 'guests')]
    public function guests(): Response
    {
        $guests = $this->userRepository->findBy(['isEnabled' => true]);

        $filteredGuests = array_filter($guests, static function ($user) {
            return in_array('ROLE_USER', $user->getRoles());
        });

        return $this->render('front/guests.html.twig', [
            'guests' => $filteredGuests
        ]);
    }

    #[Route('/guest/{id}', name: 'guest')]
    public function guest(int $id): Response
    {
        $guest = $this->entityManager->getRepository(User::class)->find($id);
        if($guest && !$guest->isEnabled()) {
            return $this->redirectToRoute('guests');
        }
        return $this->render('front/guest.html.twig', [
            'guest' => $guest
        ]);
    }

    #[Route('/portfolio/{id}', name: 'portfolio')]
    public function portfolio(?int $id = null): Response
    {
        /** @var AlbumRepository $albums */
        $albums = $this->entityManager->getRepository(Album::class)->findAlbumsWithEnabledUsers();
        $album = $id ? $this->entityManager->getRepository(Album::class)->find($id) : null;
        $user = $this->entityManager->getRepository(User::class)->findOneByRole("ROLE_ADMIN");

        $medias = $album
            ? $this->entityManager->getRepository(Media::class)->findByAlbum($album)
            : $this->entityManager->getRepository(Media::class)->findAll();
        return $this->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias
        ]);
    }

    #[Route('/media/{id}', name: 'media')]
    public function media(int $id) : Response
    {
        $media = $this->entityManager->getRepository(Media::class)->find($id);
        if($media) {
            $user = $media->getUser();
            if(!$user->isEnabled()) {
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('front/media.html.twig', [
            'media' => $media
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('front/about.html.twig');
    }
}