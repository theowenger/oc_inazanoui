<?php

namespace Unit;

use AlbumTest;
use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class AlbumClassTest extends TestCase
{
    private Album $album;

    protected function setUp(): void
    {
        $this->album = new Album();
        $this->album->setName("Test Album");
    }

    // Test de la création de l'album
    public function testAlbumCreation(): void
    {
        $this->assertInstanceOf(Album::class, $this->album);
        $this->assertNull($this->album->getId()); // L'ID doit être null au début
        $this->assertEquals('Test Album', $this->album->getName()); // Le nom est vide au début
        $this->assertInstanceOf(ArrayCollection::class, $this->album->getMedias()); // La collection des médias doit être un ArrayCollection vide
    }

    // Test des getters et setters
    public function testSetGetName(): void
    {
        $this->album->setName('Test Album');
        $this->assertEquals('Test Album', $this->album->getName());
    }

    public function testSetGetUser(): void
    {
        $user = $this->createMock(User::class); // Simule un utilisateur
        $this->album->setUser($user);
        $this->assertSame($user, $this->album->getUser());
    }

    // Test de l'ajout de média
    public function testAddMedia(): void
    {
        $media = $this->createMock(Media::class); // Simule un média
        $this->album->addMedia($media);

        $medias = $this->album->getMedias();
        $this->assertCount(1, $medias); // Un seul média doit être ajouté
    }

    // Test de la suppression de média
    public function testRemoveMedia(): void
    {
        $media = $this->createMock(Media::class); // Simule un média
        $this->album->addMedia($media);
        $this->assertCount(1, $this->album->getMedias());

        $this->album->removeMedia($media);
        $this->assertCount(0, $this->album->getMedias()); // Le média doit être supprimé
    }
}
