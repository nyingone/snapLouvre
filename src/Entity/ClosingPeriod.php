<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

// * @ORM\Entity(repositoryClass="App\Repository\ClosingPeriodRepository")

/**
 * @ORM\Entity
 */
class ClosingPeriod
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fromDat0;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $toDatex;

    /**
     * @ORM\Column(type="boolean")
     */
    private $holyDay;

    /**
     * @ORM\Column(type="boolean")
     */
    private $closingDay;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $dayOfWeek;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $info;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromDat0(): ?\DateTimeInterface
    {
        return $this->fromDat0;
    }

    public function setFromDat0(\DateTimeInterface $fromDat0): self
    {
        $this->fromDat0 = $fromDat0;

        return $this;
    }

    public function getToDatex(): ?\DateTimeInterface
    {
        return $this->toDatex;
    }

    public function setToDatex(\DateTimeInterface $toDatex): self
    {
        $this->toDatex = $toDatex;

        return $this;
    }

    public function getHolyDay(): ?bool
    {
        return $this->holyDay;
    }

    public function setHolyDay(bool $holyDay): self
    {
        $this->holyDay = $holyDay;

        return $this;
    }

    public function getClosingDay(): ?bool
    {
        return $this->holyDay;
    }

    public function setClosingDay(bool $holyDay): self
    {
        $this->holyDay = $holyDay;

        return $this;
    }

    public function getDayOfWeek(): ?int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(?int $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(string $info): self
    {
        $this->info = $info;

        return $this;
    }
}
