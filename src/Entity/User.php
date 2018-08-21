<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={
 *          "normalization_context"={"groups"={"read"}},
 *          "denormalization_context"={"groups"={"write"}}
 *     },
 *     collectionOperations={"get" = {"method"="GET"}},
 *     itemOperations={
 *          "get" = {"method"="GET"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email",
 *     message="asserts.email.alreadyused")
 * @UniqueEntity(fields="pseudo",
 *     message="asserts.pseudo.alreadyused")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDate;

    /**
     * @ORM\Column(name="email", type="string", length=80, unique=true)
     * @Assert\NotBlank(message="asserts.email.notblank")
     * @Assert\Length(max="50", maxMessage="asserts.email.toolong")
     * @Assert\Email(message="asserts.email.wrongtype")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(message="asserts.pseudo.notblank")
     * @Assert\Length(min="5", minMessage="asserts.pseudo.tooshort",
     *                max="20", maxMessage="asserts.pseudo.toolong")
     * @Groups({"read"})
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="asserts.password.notblank")
     * @Assert\Length(min="8", minMessage="asserts.password.tooshort",
     *                max="60", maxMessage="asserts.password.toolong")
     */
    private $password; //* @Assert\Regex(
      //pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/",
      //message="Use 1 upper case letter, 1 lower case letter, and 1 number"
 //)

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(max="50", maxMessage="asserts.password.toolong")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(max="50", maxMessage="asserts.password.toolong")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(max="15", maxMessage="asserts.password.toolong")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     * @Assert\Image(mimeTypesMessage="asserts.article.image.mimetype",
     *     maxSize="1M", maxSizeMessage="asserts.article.image.maxsize")
     */
    private $avatar;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advert", mappedBy="user")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $adverts;

    public function __construct()
    {
        $this->registrationDate = new \DateTime;
        $this->roles[] = '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function addRole(string $role): void
    {
        $this->roles[] = $role;
    }

    public function setFixtureRole(string $role): void
    {
        $this->roles[0] = $role;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(): void
    {
        return;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void
    {
        return;
    }

    public function isNotEnabled(): bool
    {
        $roles = $this->getRoles();

        return \in_array('', $roles, true);

    }

}
