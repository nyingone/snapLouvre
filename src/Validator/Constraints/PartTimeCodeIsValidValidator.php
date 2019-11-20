<?php

namespace App\Validator\Constraints;

use App\Services\ParamService;
use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\PartTimeCodeIsValid;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;


class PartTimeCodeIsValidValidator extends ConstraintValidator
{

    private $paramService;
    protected $partTimeCodes= [];
    protected $paramList;


    public function __construct(ParamService $paramService)
    {
        $this->paramService = $paramService;
        $this->paramList = $this->paramService->findPartTimeCodes();
        
        array_push($this->paramList, $this->partTimeCodes);

        dd( $this->partTimeCodes);
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PartTimeCodeIsValid){
            throw new UnexpectedTypeException($constraint, PartTimeCodeIsValid::class); 
        }

        if (!int($value) >= 0) {
            throw new UnexpectedValueException($value,'type');
        }
        
        if(!in_array($value, $this->partTimeCodes)):
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ this->paramList }}', $value)
            ->addViolation();
        endif;
    }
}