<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min = 3,
     *     max = 255,
     *     minMessage = "Ce commentaire est trop court. Il doit comporter {{ limit }} caractères ou plus.",
     *     maxMessage = "Ce commentaire est trop long. Il doit comporter {{ limit }} caractères ou moins."
     *     )
     * @Assert\NotNull(
     *     message = "Le commentaire ne peut pas être vide"
     * )
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Tricks::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tricks;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;

    }//end getId()


    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }


    /**
     * @param string $message parameter
     *
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }


    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }


    /**
     * @param DateTimeInterface $createdAt parameter
     *
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }


    /**
     * @param User|null $user parameter
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;

    }

}
