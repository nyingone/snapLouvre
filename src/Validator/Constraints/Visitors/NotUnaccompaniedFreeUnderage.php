<?php


namespace App\Validator\Constraints\Visitors;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotUnaccompaniedFreeUnderage extends Constraint
{
    public $message = 'unaccompanied_free_underage_visitor_not_allowed';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}