<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PartTimeCodeIsValid
{
    public $message = "Choose one of the proposed options.";
}
