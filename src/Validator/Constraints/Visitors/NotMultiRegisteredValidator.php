<?php

namespace App\Validator\Constraints;

use App\Entity\Visitor;

use App\Manager\VisitorManager;
use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\VisitorIsRegistered;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotMultiRegisteredValidator extends ConstraintValidator
{
    /** @var VisitorManager  */
    private $visitorManager;

    /** @param VisitorManager */
    public function __construct(VisitorManager $visitorManager)
    {
        $this->visitorManager = $visitorManager;
    }


    public function validate($visitor, Constraint $constraint)
    {
       
        if (!$constraint instanceof NotMultiRegistered)
        {
            throw new UnexpectedTypeException($constraint,  NotMultiRegistered::class);

        }
        if (!$visitor instanceof Visitor) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($visitor, Visitor::class);
        }
    
        if($this->visitorManager->isMultiRegisteredVisitor($visitor)) {
            $this->context->buildViolation($constraint->message)
            ->addViolation();
        }
    }


}