<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Schedule
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
    private $startingDate;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $dayOfWeek;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $partTimeCode;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    private $openingTime;

    /**
     * @ORM\Column(type="time", nullable=true)
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    private $lastEntryTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->startingDate;
    }

    public function setStartingDate(\DateTimeInterface $startingDate): self
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getDayOfWeek(): ?int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(int $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;

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

    public function getOpeningTime(): ?\DateTimeInterface
    {
        return $this->openingTime;
    }

    public function setOpeningTime(\DateTimeInterface $openingTime): self
    {
        $this->openingTime = $openingTime;

        return $this;
    }

    public function getLastEntryTime(): ?\DateTimeInterface
    {
        return $this->lastEntryTime;
    }

    public function setLastEntryTime(\DateTimeInterface $lastEntryTime): self
    {
        $this->lastEntryTime = $lastEntryTime;

        return $this;
    }
}
