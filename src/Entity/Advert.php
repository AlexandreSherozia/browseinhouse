<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *          "filters"={"advert.search"},
 *          "normalization_context"={"groups"={"read"}}
 *     },
 *     collectionOperations={"get" = {"method"="GET"}},
 *     itemOperations={"get" = {"method"="GET"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 */
class Advert
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank(message="asserts.notBlank")
     * @Groups({"read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="asserts.notBlank")
     * @Groups({"read"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="asserts.notBlank")
     * @Assert\Type(type="float", message="asserts.integer.type")
     * @Groups({"read"})
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    private $creationDate;

    /**
     * @Gedmo\Slug(fields={"title"},unique=true)
     * @ORM\Column(length=128, unique=true)
     * @Groups({"read"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo",
     *     mappedBy="advert", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="adverts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Section")
     * @Groups({"read"})
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="adverts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $user;

    /**
     * Advert constructor.
     * @param $creationDate
     */
    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->photos       = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param Photo $photo
     * @return $this
     */
    public function addPhoto(Photo $photo)
    {
        $photo->setAdvert($this);

        $this->photos[] = $photo;

        return $this;
    }

    /**
     * @param $photo
     */
    public function removePhoto($photo)
    {
        $this->photos->removeElement($photo);
    }


    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return Advert
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     * @return Advert
     */
    public function setSection($section)
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate(\DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

}
