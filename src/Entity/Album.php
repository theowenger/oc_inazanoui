<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    /**
     * @var Collection<int, Media> $medias
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'album',cascade: ['persist', 'remove'],orphanRemoval: true)]
    private Collection $medias;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "albums")]
    private ?User $user;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->setAlbum($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        // set the owning side to null (unless already changed)
        if ($this->medias->removeElement($media) && $media->getAlbum() === $this) {
            $media->setAlbum(null);
        }

        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;
        return $this;
    }
}
