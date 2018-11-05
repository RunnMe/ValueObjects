<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\Errors\InvalidFieldValue;
use Runn\ValueObjects\Exception;

/**
 * Class InvalidFieldValueTest
 * @package Tests\ValueObjects\Errors
 */
class InvalidFieldValueTest extends TestCase
{
    public function testConstructEmptyField(): void
    {
        if (PHP_VERSION_ID >= 70100) {
            $this->expectException(\ArgumentCountError::class);
        } else {
            $this->expectException(\TypeError::class);
        }
        new InvalidFieldValue();
    }

    public function testConstruct(): void
    {
        $prev = new \Exception('Previous');
        $error = new InvalidFieldValue('foo', 'bar', 'Test', 13, $prev);

        $this->assertInstanceOf(\Throwable::class, $error);
        $this->assertInstanceOf(Exception::class, $error);
        $this->assertInstanceOf(InvalidFieldValue::class, $error);

        $this->assertSame('foo', $error->getField());
        $this->assertSame('bar', $error->getValue());
        $this->assertSame('Test', $error->getMessage());
        $this->assertSame($prev, $error->getPrevious());
    }
}
