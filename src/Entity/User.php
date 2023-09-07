<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"login"}, message="Un utilisateur existe déjà avec cette identifiant")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @method string getUserIdentifier()
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\Length(
     *     min = 3,
     *     max = 45,
     *     minMessage = "Cette valeur est trop courte. Elle doit comporter {{ limit }} caractères ou plus.",
     *     maxMessage = "Cette valeur est trop longue. Elle doit comporter {{ limit }} caractères ou moins."
     *     )
     * @Assert\NotNull(
     *     message = "L'identifiant ne peut pas être vide"
     * )
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\Email(
     *  message= "L'email {{ value }} n'est pas une adresse email valide."
     * )
     * @Assert\NotNull(
     *     message = "L'email ne peut pas être vide"
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(
     *     message = "Le mot de passe ne peut pas être vide"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=Tricks::class, mappedBy="user")
     */
    private $tricks;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="user")
     */
    private $commentaires;

    /**
     * @ORM\ManyToOne(targetEntity=Rights::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rights;

    /**
     * @ORM\Column(type="boolean")
     */
    private $confirmed = false;


    /**
     *
     */
    public function __construct()
    {

        $this->tricks = new ArrayCollection();
        $this->commentaires = new ArrayCollection();

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
    public function getMail(): ?string
    {

        return $this->mail;
    }


    /**
     * @param string $mail parameter
     *
     * @return $this
     */
    public function setMail(string $mail): self
    {

        $this->mail = $mail;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {

        return $this->password;
    }


    /**
     * @param string $password parameter
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {

        $this->password = $password;

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
     * @return Collection<int, Tricks>
     */
    public function getTricks(): Collection
    {

        return $this->tricks;
    }


    /**
     * @param Tricks $trick parameter
     *
     * @return $this
     */
    public function addTrick(Tricks $trick): self
    {

        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->setUser($this);
        }

        return $this;
    }


    /**
     * @param Tricks $trick parameter
     *
     * @return $this
     */
    public function removeTrick(Tricks $trick): self
    {

        if ($this->tricks->removeElement($trick)) {
            // set the owning side to null (unless already changed)
            if ($trick->getUser() === $this) {
                $trick->setUser(null);
            }
        }

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
            $commentaire->setUser($this);
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
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @return array|string[]
     */
    public function getRoles(): array
    {

        return array($this->getRights()->getRole());
    }


    /**
     * @return Rights|null
     */
    public function getRights(): ?Rights
    {

        return $this->rights;
    }


    /**
     * @param Rights|null $rights parameter
     *
     * @return $this
     */
    public function setRights(?Rights $rights): self
    {

        $this->rights = $rights;

        return $this;
    }


    /**
     * @return string|void|null
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }


    /**
     * @return void
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {

        return $this->getLogin();
    }


    /**
     * @return string|null
     */
    public function getLogin(): ?string
    {

        return $this->login;
    }


    /**
     * @param string $login parameter
     *
     * @return $this
     */
    public function setLogin(string $login): self
    {

        $this->login = $login;

        return $this;
    }


    /**
     * @param string $name      parameter
     * @param array  $arguments parameter
     *
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }


    /**
     * @return bool|null
     */
    public function isConfirmed(): ?bool
    {

        return $this->confirmed;
    }


    /**
     * @param bool $confirmed parameter
     *
     * @return $this
     */
    public function setConfirmed(bool $confirmed): self
    {

        $this->confirmed = $confirmed;

        return $this;
    }
}
