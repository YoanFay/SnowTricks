<?php

namespace App\Entity;

use App\Repository\RightsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RightsRepository::class)
 */
class Rights
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="rights")
     */
    private $users;


    /**
     *
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();

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
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * @param string $name parameter
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }


    /**
     * @param string $role parameter
     *
     * @return $this
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }


    /**
     * @param User $user parameter
     *
     * @return $this
     */
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setRights($this);
        }

        return $this;
    }


    /**
     * @param User $user parameter
     *
     * @return $this
     */
    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            if ($user->getRights() === $this) {
                $user->setRights(null);
            }
        }

        return $this;
    }
}
