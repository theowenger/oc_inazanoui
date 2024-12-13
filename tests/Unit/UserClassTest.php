<?php

namespace Unit;

use AlbumTest;
use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserClassTest extends TestCase
{
    private User $user;
    private UserRepository $repository;

    protected function setUp(): void
    {
        $this->user = new User();
        $this->user->setUsername("userTest");

        $this->repository = $this->createMock(UserRepository::class);
    }

    // Test de l'ajout de média
    public function testAddMediaDuplicate(): void
    {
        $media = $this->createMock(Media::class);
        $this->user->addMedia($media);
        $this->user->addMedia($media);

        $this->assertCount(1, $this->user->getMedias());
    }

    public function testRemoveMedia(): void
    {
        $media = $this->createMock(Media::class);

        $this->user->addMedia($media);
        $this->user->removeMedia($media);

//        $media->expects($this->once())
//            ->method('getUser')
//            ->willReturn($this->user);

        $this->assertCount(0, $this->user->getMedias());

        $this->assertNull($media->getUser());
    }

    public function testRemoveNonExistingMedia(): void
    {
        $media = $this->createMock(Media::class);
        $this->user->removeMedia($media);

        $this->assertCount(0, $this->user->getMedias());
    }

    public function testSetMedia(): void
    {
        /** @var Media&MockObject $media */
        $media = $this->createMock(Media::class);

        /** @var ArrayCollection<int, Media> $collection */
        $collection = new ArrayCollection([$media]);
        $this->user->setMedias($collection);

        $this->assertSame($collection, $this->user->getMedias());
        $this->assertCount(1, $this->user->getMedias());
    }

    public function testAddAlbum(): void
    {
        $album = $this->createMock(Album::class);
        $this->user->addAlbum($album);

        $this->assertCount(1, $this->user->getAlbums());
    }

    public function testRemoveAlbum(): void
    {
        $album = $this->createMock(Album::class);
        $this->user->addAlbum($album);
        $this->assertCount(1, $this->user->getAlbums());

        $this->user->removeAlbum($album);
        $this->assertCount(0, $this->user->getAlbums());
    }

    public function testSetIsEnabled(): void
    {
        $this->user->setIsEnabled(true);
        $this->assertTrue($this->user->isEnabled());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws \JsonException
     * @throws Exception
     */
    public function testFindUserByRoleIfRoleIsNull(): void
    {
        $result = $this->repository->findOneByRole("ROLE_NULL");
        $this->assertNull($result);
    }
}
