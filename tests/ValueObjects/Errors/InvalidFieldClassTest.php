<?php

namespace Runn\tests\ValueObjects\Errors\InvalidFieldClass;

use Runn\Core\Exception;
use Runn\ValueObjects\Errors\InvalidFieldClass;
use Runn\ValueObjects\Errors\InvalidFieldValue;

class InvalidFieldClassTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @7.1
     */
    public function testConstructEmptyField()
    {
        if (PHP_VERSION_ID >= 70100) {
            $this->expectException(\ArgumentCountError::class);
        } else {
            $this->expectException(\TypeError::class);
        }
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