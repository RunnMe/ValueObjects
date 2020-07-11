<?php

namespace Runn\tests\ValueObjects\Errors\ComplexValueObjectFieldErrorTrait;

use Runn\Core\Exception;
use Runn\ValueObjects\Errors\ComplexValueObjectFieldErrorInterface;
use Runn\ValueObjects\Errors\ComplexValueObjectFieldErrorTrait;
use PHPUnit\Framework\TestCase;

class ComplexValueObjectFieldErrorTraitTest extends TestCase
{

    public function testGetSet()
    {
        $error = new class extends Exception implements ComplexValueObjectFieldErrorInterface {
            use ComplexValueObjectFieldErrorTrait;
        };

        $this->assertNull($error->getField());

        $error->setField('foo');
        $this->assertSame('foo', $error->getField());
    }

    public function testInvalidFieldKey()
    {
        $error = new class extends Exception implements ComplexValueObjectFieldErrorInterface {
            use ComplexValueObjectFieldErrorTrait;
        };

        $this->expectException(\TypeError::class);
        $error->setField([1, 2, 3]);
    }

}
