<?php

namespace App\Validator\Constraints;

use App\Entity\Visitor;

use App\Manager\VisitorManager;
use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\VisitorIsRegistered;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class VisitorIsRegisteredValidator
{
    /** @var VisitorManager  */
    private $visitorManager;

    public function __construct(VisitorManager $visitorManager, DateValidator $dateValidator,  CountryValidator $countryValidator)
    {
        $this->visitorManager = $visitorManager;
    }


    public function validate(Visitor $visitor, Constraint $constraint)
    {
       
        if (!$constraint instanceof VisitorIsRegistered)
        {
            throw new UnexpectedTypeException($constraint,  VisitorIsRegistered::class);

        }
        if (!is_object( $visitor)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($visitor, 'objet');
        }
    
        $this->controlKnownVisitor($visitor); 

        if(count($this->knownVisitors) > 1):
            $this->context->buildViolation($constraint->msgVisitorIsAlreadyRegistered)
            ->setParameter('{{ available }}', $this->availableVisitorNumber)
            ->addViolation();
        endif;

    }


    public function controlKnownVisitor($visitor)
    {
        $this->knownVisitors = $this->visitorManager->controlKnownVisitor($visitor);
    }
}