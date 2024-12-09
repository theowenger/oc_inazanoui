<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     *
     * @param string $role
     * @return User|null
     * @throws Exception
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \JsonException
     */
    public function findOneByRole(string $role): ?User
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM "user" WHERE roles::jsonb @> :role LIMIT 1';
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':role', json_encode([$role], JSON_THROW_ON_ERROR));

        $result = $stmt->executeQuery();

        $userData = $result->fetchAssociative();

        if (!$userData) {
            return null;
        }

        return $this->getEntityManager()->find(User::class, $userData['id']);
    }
}
