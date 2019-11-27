<?php

namespace App\Validator\Constraints\BookingOrder;

use App\Services\Interfaces\ParamServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;


class IsValidPartTimeCodeValidator extends ConstraintValidator
{

    private $paramService;
    protected $partTimeCodes= [];
    protected $paramList;


    /**
     * IsValidPartTimeCodeValidator constructor.
     * @param ParamServiceInterface $paramService
     */
    public function __construct(ParamServiceInterface $paramService)
    {
        $this->paramService = $paramService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof IsValidPartTimeCode)){
            throw new UnexpectedTypeException($constraint, IsValidPartTimeCode::class);
        }

        if (!is_int($value)) {
            throw new UnexpectedValueException($value,'type');
        }

        if($this->paramService->isValidPartTimeCode($value) !== true) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}