<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NationaliteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NationaliteRepository::class)
 * @ApiResource()
 */
class Nationalite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"listAuteurFull", "listAuteurSimple"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAuteurFull", "listAuteurSimple"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Auteur::class, mappedBy="nationalité")
     */
    private $auteurs;

    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Auteur[]
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs[] = $auteur;
            $auteur->setNationalité($this);
        }

        return $this;
    }

    public function removeAuteur(Auteur $auteur): self
    {
        if ($this->auteurs->contains($auteur)) {
            $this->auteurs->removeElement($auteur);
            // set the owning side to null (unless already changed)
            if ($auteur->getNationalité() === $this) {
                $auteur->setNationalité(null);
            }
        }

        return $this;
    }
}
