<?php

namespace App\Entity;

use App\Validator\Constraints\BookingOrders as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//  * @ORM\Entity(repositoryClass="App\Repository\BookingOrderRepository")

/**
 * @ORM\Entity
 * @CustomAssert\NotOutsideDayBookingQuotas(groups={"pre_booking"})
 * @CustomAssert\NotTooLateRegistrationForToday(groups={"pre_booking"})
 * @CustomAssert\NotAlreadySettledOrder(groups={"pre_booking"})
 * @CustomAssert\IfValidatedCannotChangeVisitorNumber(groups={"pre_booking"})
 *
 * @CustomAssert\NotMultiRegistered(groups={"guest_booking"})
 * @CustomAssert\NotUnaccompaniedUnderage(groups={"guest_booking"})
 */
class BookingOrder
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive(groups={"pre_booking"})
     * @CustomAssert\IsAllowedGuestNumber(groups={"pre_booking"})
     * Column(type="integer")
     */
    public $wishes = 1;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $orderDate;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     * @CustomAssert\NotClosedPeriod(groups={"pre_booking"})
     * @CustomAssert\NotHolyDay(groups={"pre_booking"})
     * @CustomAssert\NotOutOfBookingRange(groups={"pre_booking"})
     * @CustomAssert\NotUnsupportedReservationDay(groups={"pre_booking"})
     */
    private $expectedDate;

    /**
     * @ORM\Column(type="smallint")
     * @CustomAssert\IsValidPartTimeCode(groups={"pre_booking"})
     */
    private $partTimeCode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotNull()
     */
    private $totalAmount = 0;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $bookingRef;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validatedAt;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $extPaymentIntentRef;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $extPaymentRef;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $extPaymentStatus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $settledAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $confirmedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cancelledAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer",inversedBy="bookingOrders", cascade={"persist"} )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Assert\Type(type="App\Entity\Customer")
     * @Assert\Valid
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visitor", mappedBy="bookingOrder", orphanRemoval=false, cascade={"persist"})
     * @Assert\Valid
     */
    private $visitors;
    /**
     * @var string
     */
    private $partTimeLabel;
    /**
     * @var int
     */
    private $groupMaxAge ;


    public function __construct()
    {
        $this->orderDate = new \Datetime();
        $this->visitors = new ArrayCollection();
        $this->groupMaxAge = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getExpectedDate(): ?\DateTimeInterface
    {
        return $this->expectedDate;
    }

    public function setExpectedDate(\DateTimeInterface $expectedDate): self
    {
        $this->expectedDate = $expectedDate;

        return $this;
    }

    public function getPartTimeCode(): ?int
    {
        return $this->partTimeCode;
    }

    public function getPartTimeLabel(): ?string
    {
        return $this->partTimeLabel;
    }

    public function setPartTimeCode(int $partTimeCode): self
    {
        $this->partTimeCode = $partTimeCode;

        return $this;
    }

    public function setPartTimeLabel(string $partTimeLabel): self
    {
        $this->partTimeLabel = $partTimeLabel;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getBookingRef(): ?string
    {
        return $this->bookingRef;
    }

    public function setBookingRef(string $bookingRef): self
    {
        $this->bookingRef = $bookingRef;

        return $this;
    }

    public function getValidatedAt(): ?\DateTimeInterface
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(?\DateTimeInterface $validatedAt): self
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    public function getExtPaymentIntentRef(): ?string
    {
        return $this->extPaymentIntentRef;
    }

    public function setExtPaymentIntentRef(string $extPaymentIntentRef): self
    {
        $this->extPaymentIntentRef = $extPaymentIntentRef;

        return $this;
    }

    public function getExtPaymentRef(): ?string
    {
        return $this->extPaymentRef;
    }

    public function setExtPaymentRef(string $extPaymentRef): self
    {
        $this->extPaymentRef = $extPaymentRef;
        return $this;
    }

    public function getExtPaymentStatus(): ?string
    {
        return $this->extPaymentStatus;
    }

    public function setExtPaymentStatus(string $extPaymentStatus): self
    {
        $this->extPaymentStatus = $extPaymentStatus;
        return $this;
    }

    public function getSettledAt(): ?\DateTimeInterface
    {
        return $this->settledAt;
    }

    public function setSettledAt(?\DateTimeInterface $settledAt): self
    {
        $this->settledAt = $settledAt;
        return $this;
    }

    public function getCancelledAt(): ?\DateTimeInterface
    {
        return $this->cancelledAt;
    }

    public function setCancelledAt(?\DateTimeInterface $cancelledAt): self
    {
        $this->cancelledAt = $cancelledAt;
        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|Visitor[]
     */
    public function getVisitors(): Collection
    {
        return $this->visitors;
    }

    public function getGroupMaxAge(): int
    {
        $this->setGroupMaxAge();
        return $this->groupMaxAge;
    }

    public function setGroupMaxAge(): self
    {
        $ageMax = 0;

        foreach ($this->visitors as $visitor) {
            if ($visitor->getAgeYearsOld() > $ageMax) {
                $ageMax = $visitor->getAgeYearsOld();
            }
        }

        $this->groupMaxAge = $ageMax;
        return $this;

    }

    public function addVisitor(Visitor $visitor): self
    {
        if (!$this->visitors->contains($visitor)) {
            $this->visitors[] = $visitor;
            $visitor->setBookingOrder($this);
        }

        return $this;
    }

    public function removeVisitor(Visitor $visitor): self
    {
        if ($this->visitors->contains($visitor)) {
            $this->visitors->removeElement($visitor);
            // set the owning side to null (unless already changed)
            if ($visitor->getBookingOrder() === $this) {
                $visitor->setBookingOrder(null);
            }
        }

        return $this;
    }

    public function getWishes(): int
    {
        return $this->wishes;
    }

    public function setWishes(int $wishes): self
    {
        $this->wishes = $wishes;

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

    public function clearVisitors()
    {
        $this->visitors = new ArrayCollection();
    }
}
