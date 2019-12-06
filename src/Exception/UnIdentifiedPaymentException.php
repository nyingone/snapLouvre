<?php


namespace App\Exception;


class UnIdentifiedPaymentException extends \ErrorException
{

    /**
     * UnIdentifiedPaymentException constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
    }
}