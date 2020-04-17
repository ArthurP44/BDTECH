<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bd", mappedBy="category")
     */
    private $bds;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->bds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Bd[]
     */
    public function getBds(): Collection
    {
        return $this->bds;
    }

    public function addBd(Bd $bd): self
    {
        if (!$this->bds->contains($bd)) {
            $this->bds[] = $bd;
            $bd->setCategory($this);
        }

        return $this;
    }

    public function removeBd(Bd $bd): self
    {
        if ($this->bds->contains($bd)) {
            $this->bds->removeElement($bd);
            // set the owning side to null (unless already changed)
            if ($bd->getCategory() === $this) {
                $bd->setCategory(null);
            }
        }

        return $this;
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
}
