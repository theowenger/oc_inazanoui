<?php

declare(strict_types=1);

namespace Functional;

use Functional\FunctionalTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @extends FunctionalTestCase<object>
 */
final class LoginTest extends FunctionalTestCase
{
    public function testThatLoginShouldSucceededIfUserIsRoleAdmin(): void
    {
        $this->get('/login');

        $this->client->submitForm('Connexion', [
            '_username' => 'userTest0@gmail.com',
            '_password' => 'secret'
        ]);

        /** @var AuthorizationCheckerInterface $authorizationChecker */
        $authorizationChecker = $this->service(AuthorizationCheckerInterface::class);

        self::assertTrue($authorizationChecker->isGranted('IS_AUTHENTICATED'));

        $this->get('/logout');

        self::assertFalse($authorizationChecker->isGranted('IS_AUTHENTICATED'));
    }
    public function testThatLoginShouldSucceededIfUserIsRoleUser(): void
    {
        $this->get('/login');

        $this->client->submitForm('Connexion', [
            '_username' => 'userTest1@gmail.com',
            '_password' => 'secret'
        ]);

        /** @var AuthorizationCheckerInterface $authorizationChecker */
        $authorizationChecker = $this->service(AuthorizationCheckerInterface::class);

        self::assertTrue($authorizationChecker->isGranted('IS_AUTHENTICATED'));

        $this->get('/logout');

        self::assertFalse($authorizationChecker->isGranted('IS_AUTHENTICATED'));
    }

    public function testThatLoginShouldFailed(): void
    {
        $this->get('/login');

        $this->client->submitForm('Connexion', [
            '_username' => 'userTest0@email.com',
            '_password' => 'fail'
        ]);

        /** @var AuthorizationCheckerInterface $authorizationChecker */
        $authorizationChecker = $this->service(AuthorizationCheckerInterface::class);

        self::assertFalse($authorizationChecker->isGranted('IS_AUTHENTICATED'));
    }
}
