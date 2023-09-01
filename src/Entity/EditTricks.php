<?php

namespace App\Entity;

use App\Repository\EditTricksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EditTricksRepository::class)
 */
class EditTricks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $oldName;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $newName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oldDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $newDescription;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $oldCategory;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $newCategory;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $updatedBy;

    /**
     * @ORM\ManyToOne(targetEntity=Tricks::class, inversedBy="editTricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    public function __construct(Tricks $trick)
    {
        $this->setUpdatedAt(new \DateTime());
        $this->setOldCategory($trick->getCategory());
        $this->setOldDescription($trick->getDescription());
        $this->setOldName($trick->getName());
    }

    public function newValue($trick, $user){

        $this->setTrick($trick);
        $this->setUpdatedBy($user);
        $this->setNewCategory($trick->getCategory());
        $this->setNewDescription($trick->getDescription());
        $this->setNewName($trick->getName());

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOldName(): ?string
    {
        return $this->oldName;
    }

    public function setOldName(string $oldName): self
    {
        $this->oldName = $oldName;

        return $this;
    }

    public function getNewName(): ?string
    {
        return $this->newName;
    }

    public function setNewName(string $newName): self
    {
        $this->newName = $newName;

        return $this;
    }

    public function getOldDescription(): ?string
    {
        return $this->oldDescription;
    }

    public function setOldDescription(string $oldDescription): self
    {
        $this->oldDescription = $oldDescription;

        return $this;
    }

    public function getNewDescription(): ?string
    {
        return $this->newDescription;
    }

    public function setNewDescription(string $newDescription): self
    {
        $this->newDescription = $newDescription;

        return $this;
    }

    public function getOldCategory(): ?Categories
    {
        return $this->oldCategory;
    }

    public function setOldCategory(?Categories $oldCategory): self
    {
        $this->oldCategory = $oldCategory;

        return $this;
    }

    public function getNewCategory(): ?Categories
    {
        return $this->newCategory;
    }

    public function setNewCategory(?Categories $newCategory): self
    {
        $this->newCategory = $newCategory;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getTrick(): ?Tricks
    {
        return $this->trick;
    }

    public function setTrick(?Tricks $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
