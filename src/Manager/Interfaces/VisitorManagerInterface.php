<?php

namespace App\Manager\Interfaces;

use App\Entity\Visitor;

interface VisitorManagerInterface
{

    /**
     * @param Visitor $visitorToControl
     * @return bool
     */
    public function isMultiRegisteredVisitor(Visitor $visitorToControl) :bool;

    /**
     * @param Visitor $visitor
     * @return bool
     */
    public function isUnaccompaniedUnderage(Visitor $visitor): bool;

}
