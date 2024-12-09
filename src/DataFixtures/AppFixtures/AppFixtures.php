<?php

namespace App\DataFixtures\AppFixtures;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;

/** @codeCoverageIgnore */
class AppFixtures extends Fixture implements FixtureGroupInterface
{

    public static function getGroups(): array
    {
        return ['app'];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var User[] $users */
        $users = [];

        $this->loadUser($manager, $users);
        $this->loadMediaAndAlbums($manager, $users);

        $manager->flush();
    }

    private function loadUser(ObjectManager $manager, array &$users): void
    {

        $plainPassword = 'secret';

        for ($i = 0; $i <= 5; $i++) {
            $user = new User();

            $user->setUsername('user' . $i);
            $user->setEmail('user' . $i . '@gmail.com');

            $password = password_hash($plainPassword, PASSWORD_DEFAULT);
            $user->setPassword($password);

            $user->setRoles(['ROLE_USER']);
            if ($i === 0) {
                $user->setUsername('admin');
                $user->setEmail('admin@gmail.com');
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
        $album->setUser($user);
        $manager->persist($album);
        return $album;
    }

    private function loadMediaAndAlbums(ObjectManager $manager, array $users): void
    {
        /** @var User[] $users */
        $filesystem = new Filesystem();

        $sourcePath = dirname(__DIR__, 3) . "/public/images/ina.png";
        $uploadPath = dirname(__DIR__, 3) . "/public/uploads/";

        if (!$filesystem->exists($sourcePath)) {
            throw new \RuntimeException("File source doesnt exist : $sourcePath");
        }

        foreach ($users as $user) {
            for ($albumIndex = 1; $albumIndex <= 3; $albumIndex++) {
                $album = $this->loadAlbum($manager, $user, $albumIndex);

                for ($mediaIndex = 1; $mediaIndex <= 3; $mediaIndex++) {
                    $media = new Media();
                    $media->setUser($user);
                    $media->setAlbum($album);
                    $media->setTitle("Media " . $mediaIndex . " de l'album " . $albumIndex . " de " . $user->getUsername());

                    $fileName = md5(uniqid('', true)) . '.png';
                    $destinationPath = $uploadPath . $fileName;
                    $filesystem->copy($sourcePath, $destinationPath);
                    $media->setPath('uploads/' . $fileName);

                    $manager->persist($media);
                }
            }
        }
        $manager->flush();
    }
}