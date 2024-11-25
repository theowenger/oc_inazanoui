<?php

declare(strict_types=1);

namespace Functional;

use App\Entity\Album;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @extends FunctionalTestCase<object>
 */
final class AlbumControllerTest extends FunctionalTestCase
{
    //----------------- ADD ALBUM -----------------
    public function testReturnOkIfAdminAddNewAlbum(): void
    {
        $this->login();
        $this->get('/admin/album/add');

        $this->client->submitForm('Ajouter', [
            'album[name]' => "mediaTest",
        ]);

        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnOkIfUserAddNewAlbum():void
    {
        $this->login('userTest1@gmail.com');

        $this->get('/admin/album/add');

        $this->client->submitForm('Ajouter', [
            'album[name]' => "mediaTest",
        ]);

        self::assertResponseStatusCodeSame(302);
    }
    public function testReturnErrorIfUserNotLoggedIn():void
    {
        $this->get('/admin/album/add');

        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnErrorIfFormIsInvalid():void
    {
        $this->login();
        $this->get('/admin/album/add');

        $this->client->submitForm('Ajouter', [
            'album[name]' => "ze",
        ]);

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Ajouter', [
            'album[name]' => "zazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeaze",
        ]);
        self::assertResponseStatusCodeSame(200);
    }

    //----------------- DELETE ALBUM -----------------

    public function testReturnOkIfAdminDeleteAlbum():void
    {
        $this->login();

        $albums = self::getContainer()->get(AlbumRepository::class)->findAll();

        /** @var Album $album */
        $album = $albums[0];

        $this->get("/admin/album/delete/{$album->getId()}");

        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnOkIfUserDeleteHisOwnAlbum():void
    {
        $this->login('userTest1@gmail.com');

        $user = self::getContainer()->get(UserRepository::class)->findOneByEmail('userTest1@gmail.com');

        $albums = $user->getAlbums();

        $album = $albums[0];

        $this->get("/admin/album/delete/{$album->getId()}");

        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnErrorIfUserDeleteAnotherUserAlbum():void
    {
        //TODO: Actuellement un user peut delete un album ne lui appartenant pas
        $this->login('userTest1@gmail.com');

        $user = self::getContainer()->get(UserRepository::class)->findOneByEmail('userTest2@gmail.com');

        $albums = $user->getAlbums();

        $album = $albums[0];

        $this->get("/admin/album/delete/{$album->getId()}");

        self::assertResponseStatusCodeSame(403);
    }

    //----------------- SET ALBUM -----------------

    public function testReturnOkIsAdminSetAlbum():void
    {
        $this->login();

        $albums = self::getContainer()->get(AlbumRepository::class)->findAll();

        /** @var Album $album */
        $album = $albums[0];

        $this->get("/admin/album/update/{$album->getId()}");

        $this->client->submitForm('Modifier', [
            'album[name]' => 'Modification test',
        ]);

        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnErrorIfAdminSetAlbumWithIncorrectValues():void
    {
        $this->login();

        $albums = self::getContainer()->get(AlbumRepository::class)->findAll();

        /** @var Album $album */
        $album = $albums[0];

        $this->get("/admin/album/update/{$album->getId()}");

        $this->client->submitForm('Modifier', [
            'album[name]' => 'sa',
        ]);
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Modifier', [
            'album[name]' => 'azeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazeazesazeazeazeaz',
        ]);
        self::assertResponseStatusCodeSame(200);
    }

    public function testReturnOkIfUserSetHisOwnAlbum():void
    {
        $this->login('userTest1@gmail.com');

        $user = self::getContainer()->get(UserRepository::class)->findOneByEmail('userTest1@gmail.com');

        $albums = $user->getAlbums();

        $album = $albums[0];

        $this->get("/admin/album/update/{$album->getId()}");

        $this->client->submitForm('Modifier', [
            'album[name]' => 'album test',
        ]);
        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnErrorIfUserSetAnotherUserAlbum():void
    {
        //TODO: Actuellement un user peut set un album qui n'est pas le sien
        $this->login('userTest1@gmail.com');

        $user = self::getContainer()->get(UserRepository::class)->findOneByEmail('userTest2@gmail.com');

        $albums = $user->getAlbums();

        $album = $albums[0];

        $this->get("/admin/album/update/{$album->getId()}");

        self::assertResponseStatusCodeSame(403);
    }
}
