<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\Core\Exception;
use Runn\ValueObjects\Errors\InvalidFieldClass;

/**
 * Class InvalidFieldClassTest
 * @package Tests\ValueObjects\Errors
 */
class InvalidFieldClassTest extends TestCase
{
    public function testConstructEmptyField(): void
    {
        if (PHP_VERSION_ID >= 70100) {
            $this->expectException(\ArgumentCountError::class);
        } else {
            $this->expectException(\TypeError::class);
        }
        new InvalidFieldClass();
    }

    public function testConstruct(): void
    {
        $prev = new Exception('Previous');
        $error = new InvalidFieldClass('foo', 'bar', 'Test', 13, $prev);

        $this->assertInstanceOf(\Throwable::class, $error);
        $this->assertInstanceOf(\Runn\ValueObjects\Exception::class, $error);
        $this->assertInstanceOf(InvalidFieldClass::class, $error);

        $this->assertSame('foo', $error->getField());
        $this->assertSame('bar', $error->getClass());
        $this->assertSame('Test', $error->getMessage());
        $this->assertSame($prev, $error->getPrevious());
    }
}
