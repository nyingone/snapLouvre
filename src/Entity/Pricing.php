<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Pricing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $termDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $discounted;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $groupCode;

    /**
     * @ORM\Column(type="smallint")
     */
    private $partTimeCode;

    /**
     * @ORM\Column(type="smallint")
     */
    private $ageMin;

    /**
     * @ORM\Column(type="smallint")
     */
    private $ageMax;

    /**
     * @ORM\Column(type="integer" )
     */
    private $price;


    /**
     * @ORM\Column(type="integer")
     */
    private $ttcAmount;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTermDate(): ?\DateTimeInterface
    {
        return $this->termDate;
    }

    public function setTermDate(?\DateTimeInterface $termDate): self
    {
        $this->termDate = $termDate;

        return $this;
    }

    public function getDiscounted(): ?bool
    {
        return $this->discounted;
    }

    public function setDiscounted(bool $discounted): self
    {
        $this->discounted = $discounted;

        return $this;
    }

    public function getGroupCode(): ?string
    {
        return $this->groupCode;
    }

    public function setGroupCode(?string $groupCode): self
    {
        $this->groupCode = $groupCode;

        return $this;
    }

    public function getPartTimeCode(): ?int
    {
        return $this->partTimeCode;
    }

    public function setPartTimeCode(int $partTimeCode): self
    {
        $this->partTimeCode = $partTimeCode;

        return $this;
    }

    public function getAgeMin(): ?int
    {
        return $this->ageMin;
    }

    public function setAgeMin(int $ageMin): self
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    public function getAgeMax(): ?int
    {
        return $this->ageMax;
    }

    public function setAgeMax(int $ageMax): self
    {
        $this->ageMax = $ageMax;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTtcAmount(): ?int
    {
        return $this->ttcAmount;
    }

    public function setTtcAmount(int $ttcAmount): self
    {
        $this->ttcAmount = $ttcAmount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
