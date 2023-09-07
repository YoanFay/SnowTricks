<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagesRepository::class)
 */
class Images
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
     * @ORM\Column(type="string", length=10)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Tricks::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tricks;

    /**
     * @ORM\Column(type="boolean")
     */
    private $main = false;


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
    public function getType(): ?string
    {

        return $this->type;
    }


    /**
     * @param string $type parameter
     *
     * @return $this
     */
    public function setType(string $type): self
    {

        $this->type = $type;

        return $this;
    }


    /**
     * @return Tricks|null
     */
    public function getTricks(): ?Tricks
    {

        return $this->tricks;
    }


    /**
     * @param Tricks|null $tricks parameter
     *
     * @return $this
     */
    public function setTricks(?Tricks $tricks): self
    {

        $this->tricks = $tricks;

        return $this;
    }


    /**
     * @return bool|null
     */
    public function isMain(): ?bool
    {

        return $this->main;
    }


    /**
     * @param bool $main parameter
     *
     * @return $this
     */
    public function setMain(bool $main): self
    {

        $this->main = $main;

        return $this;
    }
}
