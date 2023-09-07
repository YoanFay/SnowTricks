<?php

namespace App\Entity;

use App\Repository\PasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PasswordRequestRepository::class)
 */
class PasswordRequest
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="boolean")
     */
    private $completed;


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
    public function getToken(): ?string
    {

        return $this->token;
    }


    /**
     * @param string $token parameter
     *
     * @return $this
     */
    public function setToken(string $token): self
    {

        $this->token = $token;

        return $this;
    }


    /**
     * @return User|null
     */
    public function getUser(): ?User
    {

        return $this->User;
    }


    /**
     * @param User|null $User parameter
     *
     * @return $this
     */
    public function setUser(?User $User): self
    {

        $this->User = $User;

        return $this;
    }


    /**
     * @return bool|null
     */
    public function isCompleted(): ?bool
    {

        return $this->completed;
    }


    /**
     * @param bool $completed parameter
     *
     * @return $this
     */
    public function setCompleted(bool $completed): self
    {

        $this->completed = $completed;

        return $this;
    }
}
