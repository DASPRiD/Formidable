<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping;

use DASPRiD\Formidable\Mapping\Constraint\ConstraintInterface;
use DASPRiD\Formidable\Mapping\MappingTrait;

trait MappingTraitTestTrait
{
    public function testVerifyingReturnsNewInstanceWithNewConstraints()
    {
        $traitA = $this->getMockForTrait(MappingTrait::class);
        $traitB = $traitA->verifying($this->getMock(ConstraintInterface::class));
        $traitC = $traitB->verifying($this->getMock(ConstraintInterface::class));

        $this->assertNotSame($traitA, $traitB);
        $this->assertNotSame($traitB, $traitC);
        $this->assertAttributeCount(0, 'constraints', $traitA);
        $this->assertAttributeCount(1, 'constraints', $traitB);
        $this->assertAttributeCount(2, 'constraints', $traitC);
    }
}
