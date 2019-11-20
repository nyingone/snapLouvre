<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VisitorIsRegistered extends Constraint
{
    public $msgVisitorIsAlreadyRegistered = 'Visitor_ is already registered for this day and booking';
   



    public function validateBy()
    {
        return 'validator.visitor_is_registered';
    }

    public function getTargets()
    {
        return self::CLAS_CONSTRAINT;
    }
}