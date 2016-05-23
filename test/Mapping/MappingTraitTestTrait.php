<?php
declare(strict_types = 1);

namespace DASPRiD\FormidableTest\Mapping;

use DASPRiD\Formidable\Mapping\Constraint\ConstraintInterface;
use DASPRiD\Formidable\Mapping\MappingInterface;

trait MappingTraitTestTrait
{
    public function testVerifyingReturnsNewInstanceWithNewConstraints()
    {
        $mappingA = $this->getInstanceForTraitTests();
        $mappingB = $mappingA->verifying($this->getMock(ConstraintInterface::class));
        $mappingC = $mappingB->verifying($this->getMock(ConstraintInterface::class));

        $this->assertNotSame($mappingA, $mappingB);
        $this->assertNotSame($mappingB, $mappingC);
        $this->assertAttributeCount(0, 'constraints', $mappingA);
        $this->assertAttributeCount(1, 'constraints', $mappingB);
        $this->assertAttributeCount(2, 'constraints', $mappingC);
    }

    abstract protected function getInstanceForTraitTests() : MappingInterface;
}
