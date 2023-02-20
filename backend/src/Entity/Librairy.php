<?php

namespace App\Entity;

use App\Repository\LibrairyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibrairyRepository::class)]
class Librairy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'librairy', targetEntity: Books::class)]
    private Collection $books_id;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    public function __construct()
    {
        $this->books_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Books>
     */
    public function getBooksId(): Collection
    {
        return $this->books_id;
    }

    public function addBooksId(Books $booksId): self
    {
        if (!$this->books_id->contains($booksId)) {
            $this->books_id->add($booksId);
            $booksId->setLibrairy($this);
        }

        return $this;
    }

    public function removeBooksId(Books $booksId): self
    {
        if ($this->books_id->removeElement($booksId)) {
            // set the owning side to null (unless already changed)
            if ($booksId->getLibrairy() === $this) {
                $booksId->setLibrairy(null);
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
