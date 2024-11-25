<?php

declare(strict_types=1);

namespace Functional;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @template T of object
 */
abstract class FunctionalTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->service(EntityManagerInterface::class);
    }

    /**
     * @param class-string<T> $id
     * @return object
     */
    protected function service(string $id): object
    {
        return $this->client->getContainer()->get($id);
    }

    /**
     * @param string $uri
     * @param array<string, mixed> $parameters
     * @return Crawler
     */
    protected function get(string $uri, array $parameters = []): Crawler
    {
        return $this->client->request('GET', $uri, $parameters);
    }

    protected function login(string $email = 'adminTest@gmail.com'): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->service(EntityManagerInterface::class);

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->client->loginUser($user);
    }

}
