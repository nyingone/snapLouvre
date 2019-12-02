<?php

namespace App\Services;


use App\Entity\Param;
use App\Services\Tools\DatComparator;
use DateTime;
use App\Services\Interfaces\ParamServiceInterface;
use App\Repository\Interfaces\ParamRepositoryInterface;

class ParamService implements ParamServiceInterface
{
    /** @var ParamRepositoryInterface */
    private $paramRepository;
    /**  */
    protected $params = [];

    private $partTimeCodes = [];
    private $partTimeArray = [];
    private $maxEntries = [];
    private $imperativeEndOfBooking;
    private $endOfBooking;
    private $startOfBooking;
    private $maxBookingVisitors;
    private $underAgeLimit;

    /**
     * @var DatComparator
     */
    private $datComparator;


    /**
     * @param ParamRepositoryInterface $paramRepository
     * @param DatComparator $datComparator
     * @throws \Exception
     */
    public function __construct(
        ParamRepositoryInterface $paramRepository,
        DatComparator $datComparator)
    {
        $this->paramRepository = $paramRepository;
        $this->datComparator = $datComparator;

        $this->params = $this->paramRepository
            ->findAll();

        $this->formatParam();
    }

    /**  */
    function formatParam()
    {
        foreach ($this->params as $param) {
            if ($param->getRefCode() == "MaxDayEntries") {
                $this->maxEntries[] = $param;
            }
            //  End of booking = today + Dly in month /end of month
            if ($param->getRefCode() == "MaxBookingOrderDly") {
                $nbMonths = $param->getNumber();
                $this->endOfBooking = new \DateTime('+' . $nbMonths . 'month');

                if ($param->getDayNum() !== ''):
                    $this->endOfBooking = new \DateTime($this->endOfBooking->format('Y-m-t'));
                endif;
            }

            if ($param->getRefCode() == "ImperativeEndOfBooking") {
                $date = $param->getExeNum() . "-" . $param->getMonthNum() . "-" . $param->getDayNum();
                $this->imperativeEndOfBooking = new \Datetime($date);
            }

            //  official start of booking service
            if ($param->getRefCode() == "StartOfBooking") {
                $date = $param->getExeNum() . "-" . $param->getMonthNum() . "-" . $param->getDayNum();
                $this->startOfBooking = new \Datetime($date);
            }

            if ($param->getRefCode() == "PartTimeCodes") {
                $list = $param->getList();
                array_push($this->partTimeCodes, $list);
            }

            if ($param->getRefCode() == "PartTimeOptions") {
                $this->partTimeArray[$param->getLabel()] = $param->getNumber();
            }

            if ($param->getRefCode() == "MaxBookingVisitors") {
                $this->maxBookingVisitors = $param->getNumber();
            }

            if ($param->getRefCode() == "UnderAgeLimit") {
                $this->underAgeLimit = $param->getNumber();
            }

        }
    }

    /**
     * @inheritDoc
     */
    public function allocateBookingNumber(): string
    {
        return $this->paramRepository->saveNumber(self::KBON);
    }

    /**
     * @return array
     */
    public function findPartTimeArray(): array
    {
        return $this->partTimeArray;
    }

    /**
     * @return datetime
     */
    public function findEndOfBooking(): datetime
    {
        if ($this->endOfBooking <= $this->imperativeEndOfBooking){
            return $this->endOfBooking;
        }

        return $this->imperativeEndOfBooking;

    }

    /**
     * @return datetime
     * @throws \Exception
     */
    public function findStartOfBooking(): datetime
    {
        if ($this->startOfBooking > new DateTime() ) {
            return $this->startOfBooking;
        }

        return new DateTime();
    }


    /**
     * @inheritDoc
     */
    public function isValidPartTimeCode(int $value): bool
    {
        $key = array_search($value, $this->partTimeCodes);
        if (isset($key)) {
            return true;
        }
        return false;
    }


    /**
     * @inheritDoc
     */
    public function findMaxAllowedGuests(): int
    {
        return $this->maxBookingVisitors;
    }

    /**
     * @inheritDoc
     */
    public function isAllowedNumberOfGuest(int $wishes): bool
    {
        if ($wishes < $this->maxBookingVisitors) {
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function findMaxDayEntries(\DateTimeInterface $expectedDate): int
    {
        $tstDate = $this->datComparator->convert($expectedDate);
        $tstMonth = substr($tstDate, 0, 6);
        $tstExe = substr($tstMonth, 0, 4);

        $dayMax = 0;
        $monthDayMax = 0;
        $yearDayMax = 0;

        foreach ($this->maxEntries as $param) {
            $refDat = rtrim($param->getExenum() . $param->getMonthNum() . $param->getDayNum());
            if ($tstDate == $refDat) :
                $dayMax = $param->getNumber();
            endif;
            if ($tstMonth == $refDat):
                $monthDayMax = $param->getNumber();
            endif;
            if ($tstExe == $refDat):
                $yearDayMax = $param->getNumber();
            endif;
        }

        if ($dayMax > 0) : return $dayMax; endif;
        if ($monthDayMax > 0) : return $monthDayMax; endif;
        if ($yearDayMax > 0) : return $yearDayMax; endif;
        return $dayMax;

    }

    /**
     * @inheritDoc
     */
    public function isOutOfRangeBooking(\DateTimeInterface $expectedDate): bool
    {
       // TODO CLEAN   dd($expectedDate, $this->findStartOfBooking(), $this->findEndOfBooking() );
        if ($this->datComparator->isLower($expectedDate, $this->findStartOfBooking()) || $this->datComparator->isHigher($expectedDate, $this->findEndOfBooking()) ){
            return true;
        }
        return false;
    }



    /**
     * @inheritDoc
     */
    public function isUnderage(int $age): bool
    {
        if ($age < $this->underAgeLimit) {
            return true;
        }

        return false;
    }
}