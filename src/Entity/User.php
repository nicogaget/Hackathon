<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="text")
     */
    private $adress;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Rdv::class, mappedBy="practitioner")
     */
    private $practitionerRdvs;

    /**
     * @ORM\OneToMany(targetEntity=Rdv::class, mappedBy="patient", orphanRemoval=true)
     */
    private $patientRdvs;

    public function __construct()
    {
        $this->practitionerRdvs = new ArrayCollection();
        $this->patientRdvs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Rdv[]
     */
    public function getPractitionerRdvs(): Collection
    {
        return $this->practitionerRdvs;
    }

    public function addPractitionerRdv(Rdv $practitionerRdv): self
    {
        if (!$this->practitionerRdvs->contains($practitionerRdv)) {
            $this->practitionerRdvs[] = $practitionerRdv;
            $practitionerRdv->setPractitioner($this);
        }

        return $this;
    }

    public function removePractitionerRdv(Rdv $practitionerRdv): self
    {
        if ($this->practitionerRdvs->contains($practitionerRdv)) {
            $this->practitionerRdvs->removeElement($practitionerRdv);
            // set the owning side to null (unless already changed)
            if ($practitionerRdv->getPractitioner() === $this) {
                $practitionerRdv->setPractitioner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rdv[]
     */
    public function getPatientRdvs(): Collection
    {
        return $this->patientRdvs;
    }

    public function addPatientRdv(Rdv $patientRdv): self
    {
        if (!$this->patientRdvs->contains($patientRdv)) {
            $this->patientRdvs[] = $patientRdv;
            $patientRdv->setPatient($this);
        }

        return $this;
    }

    public function removePatientRdv(Rdv $patientRdv): self
    {
        if ($this->patientRdvs->contains($patientRdv)) {
            $this->patientRdvs->removeElement($patientRdv);
            // set the owning side to null (unless already changed)
            if ($patientRdv->getPatient() === $this) {
                $patientRdv->setPatient(null);
            }
        }

        return $this;
    }
}
