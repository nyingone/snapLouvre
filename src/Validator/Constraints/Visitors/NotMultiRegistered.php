<?php

namespace App\Validator\Constraints\Visitors;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotMultiRegistered extends Constraint
{
    public $message = 'Visitor_ is already registered for this day and booking';


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}