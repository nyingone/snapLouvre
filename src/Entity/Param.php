<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Param
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $refCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $exeNum;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $monthNum;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $dayNum;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $list;

 
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefCode(): ?string
    {
        return $this->refCode;
    }

    public function setRefCode(string $refCode): self
    {
        $this->refCode = $refCode;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getExeNum(): ?string
    {
        return $this->exeNum;
    }

    public function setExeNum(string $exeNum): self
    {
        $this->exeNum = $exeNum;

        return $this;
    }

    public function getMonthNum(): ?string
    {
        return $this->monthNum;
    }

    public function setMonthNum(string $monthNum): self
    {
        $this->monthNum = $monthNum;

        return $this;
    }

    public function getDayNum(): ?string
    {
        return $this->dayNum;
    }

    public function setDayNum(string $dayNum): self
    {
        $this->dayNum = $dayNum;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getList(): ?string
    {
        return $this->list;
    }

    public function setList(string $list): self
    {
        $this->list = $list;

        return $this;
    }
  
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
