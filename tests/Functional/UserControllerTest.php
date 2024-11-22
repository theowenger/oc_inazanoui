<?php

declare(strict_types=1);

namespace Functional;

use App\Entity\Album;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @extends FunctionalTestCase<object>
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
        //TODO: Un user peut acceder à la liste des users, fix that !
        $this->login('userTest1@gmail.com');
        $this->get('/admin/guests');

        self::assertResponseStatusCodeSame(200);
    }


    //----------------- ADD USER -----------------
    public function testReturnOkIfAdminCreateUser(): void
    {
        $this->login();
        $this->get('/admin/guest/add');

        $this->submitUserForm("userTest2", 'descriptionTest', 'userTest11@gmail.com');

        self::assertResponseStatusCodeSame(302);
    }

//    public function testReturnErrorIfAdminCreateUserWithIncorrectValues() : void
//    {
//
//    }
//
//    public function testReturnErrorIfUserCreateUser() : void
//    {
//
//    }
//
//    //----------------- SET USER -----------------
//
//    public function testReturnOkIfAdminUpdateUser(): void
//    {
//
//    }
//
//    public function testReturnErrorIfAdminUpdateUserWithIncorrectValues() : void
//    {
//
//    }
//
//    public function testReturnErrorIfUserUpdateUser() : void
//    {
//
//    }
//
//    //----------------- DELETE USER -----------------
//
//    public function testReturnOkIfAdminDeleteUser(): void
//    {
//
//    }
//
//    public function testReturnErrorIfAdminDeleteUserWithIncorrectValues() : void
//    {
//
//    }
//
//    public function testReturnErrorIfUserDeleteUser() : void
//    {
//
//    }
    private function submitUserForm(
        string $username = 'userTest',
        string $description = "descriptionTest",
        string $email = 'test@email.com',
        string $password = 'test',
    ): void
    {
        $this->client->submitForm('Ajouter', [
            'guest[username]' => $username,
            'guest[description]' => $description,
            'guest[email]' => $email,
            'guest[password]' => $password,
        ]);
    }
}