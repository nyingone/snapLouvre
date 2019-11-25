<?php

namespace App\Validator\Constraints;

use App\Entity\Visitor;

use App\Manager\VisitorManager;
use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\VisitorIsRegistered;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class VisitorIsRegisteredOnlyOnceValidator
{
    /** @var VisitorManager  */
    private $visitorManager;

    /** @param VisitorManager */
    public function __construct(VisitorManager $visitorManager)
    {
        $this->visitorManager = $visitorManager;
    }


    public function validate(Visitor $visitor, Constraint $constraint)
    {
       
        if (!$constraint instanceof VisitorIsRegisteredOnlyOnce)
        {
            throw new UnexpectedTypeException($constraint,  VisitorIsRegisteredOnlyOnce::class);

        }
        if (!is_object( $visitor)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($visitor, 'objet');
        }
    
        if($this->visitorManager->isMultiRegisteredVisitor($visitor)) {
            $this->context->buildViolation($constraint->msgVisitorIsAlreadyRegistered)
            ->setParameter('{{ available }}', $this->availableVisitorNumber)
            ->addViolation();
        }
    }


}