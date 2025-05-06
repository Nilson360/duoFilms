<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slogan = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?int $yaersExperience = null;

    #[ORM\Column]
    private ?int $cpmpletedProjects = null;

    #[ORM\Column(nullable: true)]
    private ?int $satisfiedClients = null;

    #[ORM\Column(nullable: true)]
    private ?int $deliveredPhotos = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    /**
     * @var Collection<int, SocialLink>
     */
    #[ORM\OneToMany(targetEntity: SocialLink::class, mappedBy: 'company')]
    private Collection $socialLinks;

    /**
     * @var Collection<int, Partner>
     */
    #[ORM\OneToMany(targetEntity: Partner::class, mappedBy: 'company')]
    private Collection $partner;

    public function __construct()
    {
        $this->socialLinks = new ArrayCollection();
        $this->partner = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(string $slogan): static
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getYaersExperience(): ?int
    {
        return $this->yaersExperience;
    }

    public function setYaersExperience(int $yaersExperience): static
    {
        $this->yaersExperience = $yaersExperience;

        return $this;
    }

    public function getCpmpletedProjects(): ?int
    {
        return $this->cpmpletedProjects;
    }

    public function setCpmpletedProjects(int $cpmpletedProjects): static
    {
        $this->cpmpletedProjects = $cpmpletedProjects;

        return $this;
    }

    public function getSatisfiedClients(): ?int
    {
        return $this->satisfiedClients;
    }

    public function setSatisfiedClients(?int $satisfiedClients): static
    {
        $this->satisfiedClients = $satisfiedClients;

        return $this;
    }

    public function getDeliveredPhotos(): ?int
    {
        return $this->deliveredPhotos;
    }

    public function setDeliveredPhotos(?int $deliveredPhotos): static
    {
        $this->deliveredPhotos = $deliveredPhotos;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, SocialLink>
     */
    public function getSocialLinks(): Collection
    {
        return $this->socialLinks;
    }

    public function addSocialLink(SocialLink $socialLink): static
    {
        if (!$this->socialLinks->contains($socialLink)) {
            $this->socialLinks->add($socialLink);
            $socialLink->setCompany($this);
        }

        return $this;
    }

    public function removeSocialLink(SocialLink $socialLink): static
    {
        if ($this->socialLinks->removeElement($socialLink)) {
            // set the owning side to null (unless already changed)
            if ($socialLink->getCompany() === $this) {
                $socialLink->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partner>
     */
    public function getPartner(): Collection
    {
        return $this->partner;
    }

    public function addPartner(Partner $partner): static
    {
        if (!$this->partner->contains($partner)) {
            $this->partner->add($partner);
            $partner->setCompany($this);
        }

        return $this;
    }

    public function removePartner(Partner $partner): static
    {
        if ($this->partner->removeElement($partner)) {
            // set the owning side to null (unless already changed)
            if ($partner->getCompany() === $this) {
                $partner->setCompany(null);
            }
        }

        return $this;
    }
}
