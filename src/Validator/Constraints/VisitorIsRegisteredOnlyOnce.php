<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VisitorIsRegisteredOnlyOnce extends Constraint
{
    public $msgVisitorIsAlreadyRegistered = 'Visitor_ is already registered for this day and booking';


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}