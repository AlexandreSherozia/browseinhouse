<?php

namespace App\Entity;

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
     * @ORM\Column(type="array")
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advert", mappedBy="category")
     */
    private $adverts;

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

    public function getSection(): ?array
    {
        return $this->section;
    }

    public function setSection(array $section): self
    {
        $this->section = $section;

        return $this;
    }
}
