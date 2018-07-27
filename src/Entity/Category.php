<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
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
     */
    private $label;

    /**
     * @ORM\Column(type="string")
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advert", mappedBy="category")
     */
    private $adverts;

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

    public function getSection()
    {
        return $this->section;
    }

    public function setSection($section): self
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdverts()
    {
        return $this->adverts;
    }

    /**
     * @param mixed $adverts
     * @return Category
     */
    public function addAdvert(Advert $advert)
    {
        $this->adverts[] = $advert;

        $advert->setCategory($this);

        return $this;
    }


}
