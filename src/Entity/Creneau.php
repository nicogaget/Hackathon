<?php

namespace App\Entity;

use App\Repository\CreneauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CreneauRepository::class)
 */
class Creneau
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=Rdv::class, mappedBy="creneau")
     */
    private $creneauRdvs;

    public function __construct()
    {
        $this->creneauRdvs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Rdv[]
     */
    public function getCreneauRdvs(): Collection
    {
        return $this->creneauRdvs;
    }

    public function addCreneauRdv(Rdv $creneauRdv): self
    {
        if (!$this->creneauRdvs->contains($creneauRdv)) {
            $this->creneauRdvs[] = $creneauRdv;
            $creneauRdv->setCreneau($this);
        }

        return $this;
    }

    public function removeCreneauRdv(Rdv $creneauRdv): self
    {
        if ($this->creneauRdvs->contains($creneauRdv)) {
            $this->creneauRdvs->removeElement($creneauRdv);
            // set the owning side to null (unless already changed)
            if ($creneauRdv->getCreneau() === $this) {
                $creneauRdv->setCreneau(null);
            }
        }

        return $this;
    }
}
