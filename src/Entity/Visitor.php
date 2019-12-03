<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\Visitors as CustomAssert;


/**
 * @ORM\Entity
 * @CustomAssert\NotMultiRegistered(groups={"registration"})
 */
class Visitor 
{
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue()
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
    * @ORM\Column(type="string", length=255)
    * @Assert\NotBlank(message="Enter first Name please.")
    * @Assert\Length(
    *      min = 2,
    *      max = 50,
    *      minMessage = "Your firstname must be at least {{ limit }} characters long",
    *      maxMessage = "Your firstname cannot be longer than {{ limit }} characters"
    * )
    */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your lastname must be at least {{ limit }} characters long",
     *      maxMessage = "Your lastname cannot be longer than {{ limit }} characters"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\NotBlank
     * @Assert\Country
     */
    private $country;

    /**
     * @ORM\Column(type="boolean")
     */
    private $discounted;

    /**
     * @Assert\NotBlank(message="Booking is impossible, no tarif found.")
     * Column(type="string", length=2)
     */
    public $tariffCode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cost;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $confirmedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ticketRef;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cancelled = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BookingOrder", inversedBy="visitors")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $bookingOrder;

    private $ageYearsOld = 0;


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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;
        
        $this->setAgeYearsOld();

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    public function getTariffCode(): ?string
    {
        return $this->tariffCode;
    }

    public function setTariffCode($tariffCode)
    {
        $this->tariffCode = $tariffCode;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(?int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getConfirmedAt(): ?\DateTimeInterface
    {
        return $this->confirmedAt;
    }

    public function setConfirmedAt(?\DateTimeInterface $confirmedAt): self
    {
        $this->confirmedAt = $confirmedAt;

        return $this;
    }

    public function getTicketRef(): ?string
    {
        return $this->ticketRef;
    }

    public function setTicketRef(?string $ticketRef): self
    {
        $this->ticketRef = $ticketRef;

        return $this;
    }

    public function getCancelled(): ?bool
    {
        return $this->cancelled;
    }

    public function setCancelled(bool $cancelled): self
    {
        $this->cancelled = $cancelled;

        return $this;
    }

    public function getBookingOrder(): ?BookingOrder
    {
        return $this->bookingOrder;
    }

    public function setBookingOrder(?BookingOrder $bookingOrder): self
    {
        $this->bookingOrder = $bookingOrder;

        return $this;
    }

    /**  */
    public function getAgeYearsOld(): ?int
    {
       return $this->ageYearsOld;
    }


    public function getName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    private function setAgeYearsOld()
    {
        $this->ageYearsOld = $this->birthDate->diff(new DateTime())->y;

    }

    public function setTariff(array $findVisitorTariff)
    {
        $this->tariffCode = array_key_first($findVisitorTariff);
        $this->cost = $findVisitorTariff[$this->tariffCode];
    }
}
