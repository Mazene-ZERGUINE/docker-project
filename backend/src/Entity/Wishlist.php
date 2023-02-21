<?php

namespace App\Entity;

use App\Repository\WishlistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishlistRepository::class)]
class Wishlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'wishlist', targetEntity: user::class)]
    private Collection $user_id;

    #[ORM\OneToMany(mappedBy: 'wishlist', targetEntity: books::class)]
    private Collection $isbn;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->isbn = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, user>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(user $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id->add($userId);
            $userId->setWishlist($this);
        }

        return $this;
    }

    public function removeUserId(user $userId): self
    {
        if ($this->user_id->removeElement($userId)) {
            // set the owning side to null (unless already changed)
            if ($userId->getWishlist() === $this) {
                $userId->setWishlist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, books>
     */
    public function getIsbn(): Collection
    {
        return $this->isbn;
    }

    public function addIsbn(books $isbn): self
    {
        if (!$this->isbn->contains($isbn)) {
            $this->isbn->add($isbn);
            $isbn->setWishlist($this);
        }

        return $this;
    }

    public function removeIsbn(books $isbn): self
    {
        if ($this->isbn->removeElement($isbn)) {
            // set the owning side to null (unless already changed)
            if ($isbn->getWishlist() === $this) {
                $isbn->setWishlist(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
