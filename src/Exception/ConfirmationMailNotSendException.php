<?php


namespace App\Exception;


class ConfirmationMailNotSendException
{

    /**
     * ConfirmationMailNotSendException constructor.
     * @param string|null $getBookingRef
     */
    public function __construct (?string $getBookingRef)
    {
    }
}