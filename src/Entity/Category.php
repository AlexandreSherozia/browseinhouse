<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(attributes={
 *          "normalization_context"={"groups"={"read"}},
 *          "denormalization_context"={"groups"={"write"}}
 *     },
 *     collectionOperations={"get" = {"method"="GET"}},
 *     itemOperations={
 *          "get" = {"method"="GET"}
 *     }
 * )
 * @ORM\Entity()
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"read"})
     */
    private $label;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advert", mappedBy="category")
     */
    private $adverts;

    /**
     * @param mixed $id
     * @return Category
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Category constructor.
     * @param $adverts
     */
    public function __construct()
    {
        $this->adverts = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdverts()
    {
        return $this->adverts;
    }

    /**
     * @param Advert $advert
     * @return $this
     */
    public function addAdvert(Advert $advert)
    {
        $this->adverts[] = $advert;

        $advert->setCategory($this);

        return $this;
    }

}
