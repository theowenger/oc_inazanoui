<?php

namespace Functional;

use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class RedirectTest extends FunctionalTestCase
{

    public function testRedirectToGuestsIfUserIsEnabledOnGuestIdPage() : void
    {
        $this->login();

        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'banUserTest@gmail.com']);

        $this->assertNotNull($user);
        $this->assertFalse($user->isEnabled());

        $this->get('/guest/' . $user->getId());
        self::assertResponseRedirects('/guests');
    }

    public function testRedirectToHomeIfUserIsEnabledOnMediaIdPage() : void
    {
        $this->login();
        $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'banUserTest@gmail.com']);

        $medias = $user->getMedias();

        $media = $medias[0];
        $this->get('/media/' . $media->getId());
        $expectedUrl = self::getContainer()->get('router')->generate('home');
        self::assertResponseRedirects($expectedUrl);
    }
}
