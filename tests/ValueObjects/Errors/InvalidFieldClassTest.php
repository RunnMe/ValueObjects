<?php

namespace Runn\tests\ValueObjects\Errors\InvalidFieldClass;

use PHPUnit\Framework\TestCase;
use Runn\Core\Exception;
use Runn\ValueObjects\Errors\InvalidFieldClass;

class InvalidFieldClassTest extends TestCase
{

    public function testConstructEmptyField()
    {
        $this->expectException(\ArgumentCountError::class);
        $error = new InvalidFieldClass();
    }

    public function testConstruct()
    {
        $prev = new Exception('Previous');
        $error = new InvalidFieldClass('foo', 'bar', 'Test', 13, $prev);

        $this->assertInstanceOf(\Throwable::class, $error);
        $this->assertInstanceOf(\Runn\ValueObjects\Exception::class, $error);
        $this->assertInstanceOf(\Runn\ValueObjects\Errors\InvalidFieldClass::class, $error);

        $this->assertSame('foo', $error->getField());
        $this->assertSame('bar', $error->getClass());
        $this->assertSame('Test', $error->getMessage());
        $this->assertSame($prev, $error->getPrevious());
    }

}
