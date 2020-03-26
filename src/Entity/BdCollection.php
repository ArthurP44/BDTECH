<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BdCollectionRepository")
 */
class BdCollection
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
     * @ORM\OneToMany(targetEntity="App\Entity\Bd", mappedBy="collection")
     */
    private $bds;

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
            $bd->setCollection($this);
        }

        return $this;
    }

    public function removeBd(Bd $bd): self
    {
        if ($this->bds->contains($bd)) {
            $this->bds->removeElement($bd);
            // set the owning side to null (unless already changed)
            if ($bd->getCollection() === $this) {
                $bd->setCollection(null);
            }
        }

        return $this;
    }
}
