<?php

declare(strict_types=1);

namespace Functional;

use App\Entity\Album;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @exl'objectif errant des horizons sans limites, parcourt le globe uniquement au gré des murmures de la nature, capturant le souffle du monde dans des cadres silencieux.

découvrirtends FunctionalTestCase<object>
 */
final class UserControllerTest extends FunctionalTestCase
{
    //----------------- GET USER -----------------

    public function testReturnOkIfAdminGetUsers(): void
    {
        $this->login();
        $this->get('/admin/guests');

        self::assertResponseStatusCodeSame(200);
    }

    public function testReturnRedirectIfUserGetUsers(): void
    {
        $this->login('userTest1@gmail.com');
        $this->get('/admin/guests');

        self::assertResponseStatusCodeSame(403);
    }


    //----------------- ADD USER -----------------
    public function testReturnOkIfAdminCreateUser(): void
    {
        $this->login();
        $this->get('/admin/guest/add');

        $this->submitUserForm("userTest2", 'descriptionTest', 'userTest11@gmail.com', 'secret');

        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnErrorIfAdminCreateUserWithIncorrectValues() : void
    {
        $this->login();
        $this->get('/admin/guest/add');

        $this->submitUserForm("", '', 'userTest11@gmail.com', 'se');

        self::assertResponseStatusCodeSame(200);
    }
//
    public function testReturnErrorIfUserCreateUser() : void
    {
        $this->login('userTest1@gmail.com');
        $this->get('/admin/guest/add');

        self::assertResponseStatusCodeSame(403);
    }
//
//    //----------------- SET USER -----------------
//
    public function testReturnOkIfAdminUpdateUser(): void
    {
        $this->login();
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'userTest2']);
        $this->get('/admin/guest/update/' . $user->getId());

        $this->submitUserForm("userTest2", 'descriptionTest', 'userTest111@gmail.com', 'coucou', 'Modifier');

        self::assertResponseStatusCodeSame(302);
    }
//
    public function testReturnErrorIfAdminUpdateUserWithIncorrectValues() : void
    {
        $this->login();
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'userTest2']);
        $this->get('/admin/guest/update/' . $user->getId());

        $this->submitUserForm("userTest2", 'descriptionTest', 'ezoihlkvs', 'coucou', 'Modifier');

        self::assertResponseStatusCodeSame(200);
    }

    public function testReturnErrorIfUserUpdateUser() : void
    {
        $this->login("userTest1@gmail.com");
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'userTest2']);
        $this->get('/admin/guest/update/' . $user->getId());

        self::assertResponseStatusCodeSame(403);
    }
//
//    //----------------- DELETE USER -----------------
//
    public function testReturnOkIfAdminDeleteUser(): void
    {
        $this->login();
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'userTest3']);
        $this->get('/admin/guest/delete/' . $user->getId());
        self::assertResponseStatusCodeSame(302);
    }

    public function testReturnErrorIfUserDeleteUser() : void
    {
        $this->login("userTest1@gmail.com");
        $user = $this->getEntityManager()->getRepository(User::class)->findOneBy(['username' => 'userTest4']);
        $this->get('/admin/guest/delete/' . $user->getId());
        self::assertResponseStatusCodeSame(403);
    }
    private function submitUserForm(
        string $username = 'userTest',
        string $description = "descriptionTest",
        string $email = 'test@email.com',
        string $password = 'test',
        string $btnName = 'Ajouter'
    ): void
    {
        $this->client->submitForm($btnName, [
            'guest[username]' => $username,
            'guest[description]' => $description,
            'guest[email]' => $email,
            'guest[password]' => $password,
        ]);
    }
}
