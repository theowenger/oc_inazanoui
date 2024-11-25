<?php

declare(strict_types=1);

namespace Functional;

use App\Entity\Album;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @extends FunctionalTestCase<object>
 */
final class MediaControllerTest extends FunctionalTestCase
{
    //----------------- ADD MEDIA -----------------
    public function testReturnOkIfAdminAddNewMedia(): void
    {
        $this->login();
        $this->get('/admin/media/add');

        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'userTest1@gmail.com']);

        $albums = self::getContainer()->get(AlbumRepository::class)->findAll();

        $album = $albums[array_rand($albums)];

        $uploadedFile = $this->addNewUploadedFile('/../../public/images/ina.png');

        $this->submitMediaForm($user, $album, $uploadedFile);

        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnErrorIfUploadedFileIsTooLarge(): void
    {
        $this->login();
        $this->get('/admin/media/add');

        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'userTest1@gmail.com']);

        $albums = self::getContainer()->get(AlbumRepository::class)->findAll();

        $album = $albums[array_rand($albums)];

        $uploadedFile = $this->addNewUploadedFile('/../../public/images/test-files/too-large.jpg');

        $this->submitMediaForm($user, $album, $uploadedFile);

        self::assertResponseStatusCodeSame(200);
    }

    public function testReturnErrorIfUploadedFileIsInvalid(): void
    {
        $this->login();
        $this->get('/admin/media/add');

        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'userTest1@gmail.com']);

        $albums = self::getContainer()->get(AlbumRepository::class)->findAll();

        $album = $albums[array_rand($albums)];

        $uploadedFile = $this->addNewUploadedFile('/../../public/images/test-files/jump-sound.mp3');

        $this->submitMediaForm($user, $album, $uploadedFile);

        self::assertResponseStatusCodeSame(200);
    }

    public function testReturnOkIfUserAddNewMediaForHisAccount(): void
    {
        $this->login('userTest3@gmail.com');
        $this->get('/admin/media/add');

        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'userTest3@gmail.com']);
        $album = self::getContainer()->get(AlbumRepository::class)->findOneBy(['user' => $user->getId()]);

        $uploadedFile = $this->addNewUploadedFile('/../../public/images/ina.png');

        $this->submitMediaForm($user, $album, $uploadedFile);
        self::assertResponseStatusCodeSame(302);
    }

//    public function testReturnErrorIfUserAddNewMediaForAnotherAccount(): void
//    {
//        //TODO: Gerer le fait qu'un user ne puisse pas ajouter de medias sur un autre album que le sien
//        $this->login('userTest3@gmail.com');
//        $this->get('/admin/media/add');
//
//        $loggedUser = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'userTest3@gmail.com']);
//
//        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'userTest2@gmail.com']);
//        $album = self::getContainer()->get(AlbumRepository::class)->findOneBy(['user' => $user->getId()]);
//
//        $uploadedFile = $this->addNewUploadedFile('/../../public/images/ina.png');
//
//        $this->submitMediaForm($loggedUser, $album, $uploadedFile);
//        self::assertResponseStatusCodeSame(403);
//    }

//    //----------------- DELETE MEDIA -----------------
    public function testReturnOkIfAdminDeleteMedia(): void
    {
        $this->login();

        $medias = self::getContainer()->get(MediaRepository::class)->findAll();
        $media = $medias[array_rand($medias)];
        $this->get("/admin/media/delete/{$media->getId()}");

        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnOKIfUserDeleteMediaForHisAccount(): void
    {
        $this->login('userTest1@gmail.com');
        /** @var User $user */
        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'userTest1@gmail.com']);
        $medias = $user->getMedias();
        $media = $medias[0];

        $this->get("/admin/media/delete/{$media->getId()}");

        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnErrorIfUserDeleteMediaForAnotherAccount(): void
    {
        //TODO: A priori un User peut delete un media qui ne lui appartient pas.
        $this->login('userTest1@gmail.com');
        /** @var User $user */
        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'userTest4@gmail.com']);
        $medias = $user->getMedias();
        $media = $medias[0];

        $this->get("/admin/media/delete/{$media->getId()}");

        self::assertResponseStatusCodeSame(403);
    }

    private function addNewUploadedFile(string $path): UploadedFile
    {
        return new UploadedFile(
            __DIR__ . $path,
            'file.jpg',
            'image/jpeg',
            null,
            true,
        );
    }
    private function submitMediaForm(User $user, Album $album, UploadedFile $uploadedFile): void
    {
        $this->client->submitForm('Ajouter', [
            'media[user]' => $user->getId(),
            'media[album]' => $album->getId(),
            'media[title]' => 'test title',
            'media[file]' => $uploadedFile,
        ]);
    }
}
