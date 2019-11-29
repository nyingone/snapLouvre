<?php


namespace App\Validator\Constraints\Visitors;

use App\Entity\Visitor;
use App\Manager\VisitorManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotUnaccompaniedFreeUnderageValidator extends ConstraintValidator
{
    /** @var VisitorManager  */
    private $visitorManager;

    public function __construct(VisitorManager $visitorManager)
    {
        $this->visitorManager = $visitorManager;
    }

    public function validate($visitor, Constraint $constraint)
    {

        if (!$constraint instanceof NotUnaccompaniedFreeUnderage) {
            throw new UnexpectedTypeException($constraint, NotUnaccompaniedFreeUnderage::class);

        }
        if (!$visitor instanceof Visitor) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($visitor, Visitor::class);
        }

        if ( $this->visitorManager->isUnaccompaniedUnderage($visitor) ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}