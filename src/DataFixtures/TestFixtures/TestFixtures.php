<?php

namespace App\DataFixtures\TestFixtures;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $users = [];

        $this->loadUser($manager, $users);
        $this->loadMediaAndAlbums($manager, $users);

        $manager->flush();
    }

    private function loadUser(ObjectManager $manager, array &$users): void
    {
        $password = 'secret';

        for($i = 0; $i <= 5; $i++) {
            $user = new User();

            $user->setUsername('userTest' . $i);
            $user->setEmail('userTest'. $i.'@gmail.com');

            $password = password_hash($password, PASSWORD_DEFAULT);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            if($i === 0) {
                $user->setRoles(['ROLE_ADMIN']);
            }
            $users[] = $user;
            $manager->persist($user);
        }
    }

    private function loadAlbum(ObjectManager $manager, User $user, int $albumIndex): Album
    {
        $album = new Album();
        $album->setName("Album de " . $user->getUsername() . " #" . $albumIndex);
        $manager->persist($album);
        return $album;
    }

    private function loadMediaAndAlbums(ObjectManager $manager, array $users): void
    {
        foreach ($users as $user) {
            for ($albumIndex = 1; $albumIndex <= 3; $albumIndex++) {
                $album = $this->loadAlbum($manager, $user, $albumIndex);

                for ($mediaIndex = 1; $mediaIndex <= 3; $mediaIndex++) {
                    $media = new Media();
                    $media->setUser($user);
                    $media->setAlbum($album);
                    $media->setTitle("Media " . $mediaIndex . " de l'album " . $albumIndex . " de " . $user->getUsername());
                    $media->setPath('uploads/58caf3738ee0951dab898974b2ae50e0.png');

                    $manager->persist($media);
                }
            }
        }
    }
}