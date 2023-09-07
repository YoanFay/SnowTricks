<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use App\Service\UtilitaireService;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(fields={"name"}, message="Un trick existe déjà avec ce nom")
 * @ORM\Entity(repositoryClass=TricksRepository::class)
 */
class Tricks
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\Length(
     *     min = 3,
     *     max = 20,
     *     minMessage = "Cette valeur est trop courte. Elle doit comporter {{ limit }} caractères ou plus.",
     *     maxMessage = "Cette valeur est trop longue. Elle doit comporter {{ limit }} caractères ou moins."
     *     )
     * @Assert\NotNull(
     *     message = "Le nom ne peut pas être vide"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min = 3,
     *     max = 255,
     *     minMessage = "Cette valeur est trop courte. Elle doit comporter {{ limit }} caractères ou plus.",
     *     maxMessage = "Cette valeur est trop longue. Elle doit comporter {{ limit }} caractères ou moins."
     *     )
     * @Assert\NotNull(
     *     message = "La description ne peut pas être vide"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="tricks")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="tricks")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="tricks")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity=EditTricks::class, mappedBy="trick", orphanRemoval=true)
     */
    private $editTricks;


    /**
     *
     */
    public function __construct()
    {

        $this->setCreatedAt(new \DateTime());
        $this->commentaires = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->editTricks = new ArrayCollection();

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
    public function getDescription(): ?string
    {

        return $this->description;
    }


    /**
     * @param string $description parameter
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {

        $this->description = $description;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {

        return $this->slug;
    }


    /**
     * @param string $slug parameter
     *
     * @return $this
     */
    public function setSlug(string $slug): self
    {

        $this->slug = $slug;

        return $this;
    }


    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {

        return $this->created_at;
    }


    /**
     * @param DateTimeInterface $created_at parameter
     *
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $created_at): self
    {

        $this->created_at = $created_at;

        return $this;
    }


    /**
     * @return DateTimeInterface|null
     */
    public function getDeletedAt(): ?DateTimeInterface
    {

        return $this->deleted_at;
    }


    /**
     * @param DateTimeInterface|null $deleted_at parameter
     *
     * @return $this
     */
    public function setDeletedAt(?DateTimeInterface $deleted_at): self
    {

        $this->deleted_at = $deleted_at;

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


    /**
     * @return Categories|null
     */
    public function getCategory(): ?Categories
    {

        return $this->category;
    }


    /**
     * @param Categories|null $category parameter
     *
     * @return $this
     */
    public function setCategory(?Categories $category): self
    {

        $this->category = $category;

        return $this;
    }


    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {

        return $this->commentaires;
    }


    /**
     * @param Commentaire $commentaire parameter
     *
     * @return $this
     */
    public function addCommentaire(Commentaire $commentaire): self
    {

        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setTricks($this);
        }

        return $this;
    }


    /**
     * @param Commentaire $commentaire parameter
     *
     * @return $this
     */
    public function removeCommentaire(Commentaire $commentaire): self
    {

        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getTricks() === $this) {
                $commentaire->setTricks(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {

        return $this->images;
    }


    /**
     * @param Images $image parameter
     *
     * @return $this
     */
    public function addImage(Images $image): self
    {

        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTricks($this);
        }

        return $this;
    }


    /**
     * @param Images $image parameter
     *
     * @return $this
     */
    public function removeImage(Images $image): self
    {

        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getTricks() === $this) {
                $image->setTricks(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {

        return $this->videos;
    }


    /**
     * @param Video $video parameter
     *
     * @return $this
     */
    public function addVideo(Video $video): self
    {

        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTricks($this);
        }

        return $this;
    }


    /**
     * @param Video $video parameter
     *
     * @return $this
     */
    public function removeVideo(Video $video): self
    {

        if ($this->videos->removeElement($video)) {
            if ($video->getTricks() === $this) {
                $video->setTricks(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, EditTricks>
     */
    public function getEditTricks(): Collection
    {

        return $this->editTricks;
    }


    /**
     * @param EditTricks $editTrick parameter
     *
     * @return $this
     */
    public function addEditTrick(EditTricks $editTrick): self
    {

        if (!$this->editTricks->contains($editTrick)) {
            $this->editTricks[] = $editTrick;
            $editTrick->setTrick($this);
        }

        return $this;
    }


    /**
     * @param EditTricks $editTrick parameter
     *
     * @return $this
     */
    public function removeEditTrick(EditTricks $editTrick): self
    {

        if ($this->editTricks->removeElement($editTrick)) {
            if ($editTrick->getTrick() === $this) {
                $editTrick->setTrick(null);
            }
        }

        return $this;
    }
}
