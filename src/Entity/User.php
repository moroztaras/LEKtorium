<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @Vich\Uploadable
 */
class User implements UserInterface, \JsonSerializable
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string
     */
    protected $tempRoles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $password;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min="1",
     *      minMessage="First name can not be less than {{ limit }} character"
     *     )
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="1",
     *     minMessage="Last name can not be less than {{ limit }} character"
     *     )
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $region;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $comments;

    /**
     * @var File
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatarName")
     */
    private $avatarFile;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $avatarName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private $apiToken;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->roles = ['ROLE_READER'];
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return (string) $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Set region.
     *
     * @param string $region
     *
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Get TempRoles.
     *
     * @var string
     *
     * @return string
     */
    public function getTempRoles()
    {
        return (string) $this->tempRoles;
    }

    /**
     * Set TempRoles.
     *
     * @param string $tempRoles
     *
     * @return $this
     */
    public function setTempRoles($tempRoles)
    {
        $this->tempRoles = $tempRoles;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $avatarFile
     */
    public function setAvatarFile(?File $avatarFile = null): void
    {
        $this->avatarFile = $avatarFile;
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function setAvatarName(?string $avatarName): void
    {
        $this->avatarName = $avatarName;
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    /**
     * Get apiToken.
     *
     * @return null|string
     */
    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    /**
     * Set apiToken.
     *
     * @param string $apiToken
     *
     * @return User
     */
    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
          'id' => $this->getId(),
          'username' => $this->getUsername(),
          'email' => $this->getEmail(),
          'roles' => $this->getRoles(),
          'avatar' => $this->getAvatarName(),
          'api_token' => $this->getApiToken(),
          'create_at' => $this->getCreatedAt(),
        ];
    }
}
