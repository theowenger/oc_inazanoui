<?php

declare(strict_types=1);

namespace Functional;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Repository\UserRepository;

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
        $this->login('user1@gmail.com');
        $this->get('/admin/guests');

        self::assertResponseStatusCodeSame(302);
    }


    //----------------- ADD USER -----------------
    public function testReturnOkIfAdminCreateUser(): void
    {
        
    }

    public function testReturnErrorIfAdminCreateUserWithIncorrectValues() : void
    {

    }

    public function testReturnErrorIfUserCreateUser() : void
    {

    }

    //----------------- SET USER -----------------

    public function testReturnOkIfAdminUpdateUser(): void
    {

    }

    public function testReturnErrorIfAdminUpdateUserWithIncorrectValues() : void
    {

    }

    public function testReturnErrorIfUserUpdateUser() : void
    {

    }

    //----------------- DELETE USER -----------------

    public function testReturnOkIfAdminDeleteUser(): void
    {

    }

    public function testReturnErrorIfAdminDeleteUserWithIncorrectValues() : void
    {

    }

    public function testReturnErrorIfUserDeleteUser() : void
    {

    }
}
