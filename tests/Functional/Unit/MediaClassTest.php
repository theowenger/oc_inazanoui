<?php

namespace Functional\Unit;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class MediaClassTest extends TestCase
{
    private Album $album;
    private Media $media;
    private User $user;

    protected function setUp(): void
    {
        $this->media = new Media();
        $this->album = new Album();
        $this->user = new User();

        $this->media->setPath('/path/to/file');
        $this->media->setTitle('title');
        $this->media->setAlbum($this->album);
        $this->media->setUser($this->user);

        $this->album->addMedia($this->media);

        $this->user->addMedia($this->media);
    }

    public function testRemoveMedia(): void
    {
        $this->assertCount(1, $this->album->getMedias());
        $this->assertCount(1, $this->user->getMedias());

        $this->album->removeMedia($this->media);
        $this->user->removeMedia($this->media);

        $this->assertCount(0, $this->album->getMedias());
        $this->assertCount(0, $this->user->getMedias());
        $this->assertNull($this->media->getAlbum());
    }
}
