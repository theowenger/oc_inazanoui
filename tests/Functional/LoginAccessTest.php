<?php

declare(strict_types=1);

namespace Functional;

use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends FunctionalTestCase<object>
 */
final class LoginAccessTest extends FunctionalTestCase
{
    public function testReturnTrueForPublicRoutes():void
    {
        $this->get('/');
        self::assertResponseStatusCodeSame(200);
        $this->get('/login');
        self::assertResponseStatusCodeSame(200);
        $this->get('/guests');
        self::assertResponseStatusCodeSame(200);
        $this->get('/portfolio');
        self::assertResponseStatusCodeSame(200);
        $this->get('/about');
        self::assertResponseStatusCodeSame(200);

        $user = self::getContainer()->get(UserRepository::class)->findOneByEmail('userTest1@gmail.com');
        $albums = $user->getAlbums();
        $medias = $user->getMedias();

        $this->get('guest/' . $user->getId());
        self::assertResponseStatusCodeSame(200);

        $this->get('/portfolio/' . $albums[0]->getId());
        self::assertResponseStatusCodeSame(200);

        $this->get('/media/' . $medias[0]->getId());
        self::assertResponseStatusCodeSame(200);

    }
    public function testReturnFalseIfClientNotAuthenticated(): void
    {
        $this->get('/admin/album');
        self::assertResponseStatusCodeSame(302);
        $this->get('/admin/media');
        self::assertResponseStatusCodeSame(302);
        $this->get('/admin/guests');
        self::assertResponseStatusCodeSame(302);
    }
public function testReturnTrueIfClientIsAuthenticatedAsRoleAdmin(): void
    {
        $this->login();
        $this->get('/admin/album');
        self::assertResponseStatusCodeSame(200);
        $this->get('/admin/media');
        self::assertResponseStatusCodeSame(200);
        $this->get('/admin/guests');
        self::assertResponseStatusCodeSame(200);

    }
    public function testIfClientIsAuthenticatedAsRoleUser(): void
    {
        $this->login('userTest1@gmail.com');
        $this->get('/admin/album');
        self::assertResponseStatusCodeSame(200);
        $this->get('/admin/media');
        self::assertResponseStatusCodeSame(200);
        $this->get('/admin/guests');
        self::assertResponseStatusCodeSame(403);
    }
}
