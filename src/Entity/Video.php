<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 */
class Video
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=Tricks::class, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tricks;


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
    public function getLink(): ?string
    {
        return $this->link;
    }


    /**
     * @param string $link parameter
     *
     * @return $this
     */
    public function setLink(string $link): self
    {
        $this->link = $link;

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
}
