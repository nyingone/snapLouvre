<?php

namespace App\Manager;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookingDefaultManager
{
/** @var SessionInterface */
    protected $session;
/** @var ValiodatorInterface */
    protected $validator;

    public function __construct(SessionInterface $session, ValidatorInterface $validator)
    {
        $this->session = $session;
        $this->validator = $validator;

    }
}