<?php

namespace Runn\tests\ValueObjects\Errors\InvalidFieldValue;

use Runn\Core\Exception;
use Runn\ValueObjects\Errors\InvalidFieldValue;

class InvalidFieldValueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @7.1
     */
    public function testConstructEmptyField()
    {
        $this->expectException(\ArgumentCountError::class);
        $error = new InvalidFieldValue();
    }

    public function testConstruct()
    {
        $prev = new Exception('Previous');
        $error = new InvalidFieldValue('foo', 'bar', 'Test', 13, $prev);

        $this->assertInstanceOf(\Throwable::class, $error);
        $this->assertInstanceOf(\Runn\ValueObjects\Exception::class, $error);
        $this->assertInstanceOf(\Runn\ValueObjects\Errors\InvalidFieldValue::class, $error);

        $this->assertSame('foo', $error->getField());
        $this->assertSame('bar', $error->getValue());
        $this->assertSame('Test', $error->getMessage());
        $this->assertSame($prev, $error->getPrevious());
    }

}