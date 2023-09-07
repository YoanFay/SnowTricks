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


    /**
     * @param Tricks $trick parameter
     */
    public function __construct(Tricks $trick)
    {

        $this->setUpdatedAt(new \DateTime());
        $this->setOldCategory($trick->getCategory());
        $this->setOldDescription($trick->getDescription());
        $this->setOldName($trick->getName());

    }


    /**
     * @param Tricks $trick parameter
     * @param User   $user  parameter
     *
     * @return void
     */
    public function newValue(Tricks $trick, User $user)
    {

        $this->setTrick($trick);
        $this->setUpdatedBy($user);
        $this->setNewCategory($trick->getCategory());
        $this->setNewDescription($trick->getDescription());
        $this->setNewName($trick->getName());

    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {

        return $this->id;
    }


    /**
     * @return string|null
     */
    public function getOldName(): ?string
    {

        return $this->oldName;
    }


    /**
     * @param string $oldName parameter
     *
     * @return $this
     */
    public function setOldName(string $oldName): self
    {

        $this->oldName = $oldName;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getNewName(): ?string
    {

        return $this->newName;
    }


    /**
     * @param string $newName parameter
     *
     * @return $this
     */
    public function setNewName(string $newName): self
    {

        $this->newName = $newName;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getOldDescription(): ?string
    {

        return $this->oldDescription;
    }


    /**
     * @param string $oldDescription parameter
     *
     * @return $this
     */
    public function setOldDescription(string $oldDescription): self
    {

        $this->oldDescription = $oldDescription;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getNewDescription(): ?string
    {

        return $this->newDescription;
    }


    /**
     * @param string $newDescription parameter
     *
     * @return $this
     */
    public function setNewDescription(string $newDescription): self
    {

        $this->newDescription = $newDescription;

        return $this;
    }


    /**
     * @return Categories|null
     */
    public function getOldCategory(): ?Categories
    {

        return $this->oldCategory;
    }


    /**
     * @param Categories|null $oldCategory parameter
     *
     * @return $this
     */
    public function setOldCategory(?Categories $oldCategory): self
    {

        $this->oldCategory = $oldCategory;

        return $this;
    }


    /**
     * @return Categories|null
     */
    public function getNewCategory(): ?Categories
    {

        return $this->newCategory;
    }


    /**
     * @param Categories|null $newCategory parameter
     *
     * @return $this
     */
    public function setNewCategory(?Categories $newCategory): self
    {

        $this->newCategory = $newCategory;

        return $this;
    }


    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {

        return $this->updatedAt;
    }


    /**
     * @param \DateTimeInterface $updatedAt parameter
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {

        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @return User|null
     */
    public function getUpdatedBy(): ?User
    {

        return $this->updatedBy;
    }


    /**
     * @param User|null $updatedBy parameter
     *
     * @return $this
     */
    public function setUpdatedBy(?User $updatedBy): self
    {

        $this->updatedBy = $updatedBy;

        return $this;
    }


    /**
     * @return Tricks|null
     */
    public function getTrick(): ?Tricks
    {

        return $this->trick;
    }


    /**
     * @param Tricks|null $trick parameter
     *
     * @return $this
     */
    public function setTrick(?Tricks $trick): self
    {

        $this->trick = $trick;

        return $this;
    }
}
