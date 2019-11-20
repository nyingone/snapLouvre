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
    private $holiday;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $dayOfWeek;

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

    public function getHoliday(): ?bool
    {
        return $this->holiday;
    }

    public function setHoliday(bool $holiday): self
    {
        $this->holiday = $holiday;

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
}
