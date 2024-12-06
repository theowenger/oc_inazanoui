<?php

declare(strict_types=1);

namespace Functional;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @extends FunctionalTestCase<object>
 */
final class LoginAccessTest extends FunctionalTestCase
{
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
        //TODO: Seulement l'admin peut acceder à la page guest
        $this->get('/admin/guests');
        self::assertResponseStatusCodeSame(403);

    }
}
