<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 * @ApiResource()
 */
class Auteur
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
     * @Groups({"listAuteurFull", "listAuteurSimple", "listGenreFull"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"listAuteurFull", "listAuteurSimple", "listGenreFull"})
     */
    private $prenom;

    /**
     * @ORM\ManyToOne(targetEntity=Nationalite::class, inversedBy="auteurs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"listAuteurFull", "listGenreFull", "listAuteurSimple"})
     */
    private $nationalité;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="auteur")
     * @Groups({"listAuteurFull"})
     */
    private $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNationalité(): ?Nationalite
    {
        return $this->nationalité;
    }

    public function setNationalité(?Nationalite $nationalité): self
    {
        $this->nationalité = $nationalité;

        return $this;
    }

    /**
     * @return Collection|Livre[]
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->contains($livre)) {
            $this->livres->removeElement($livre);
            // set the owning side to null (unless already changed)
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }

        return $this;
    }
}
