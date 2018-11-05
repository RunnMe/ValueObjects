<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\Values\DateValue;

/**
 * Class DateValueTest
 * @package Tests\ValueObjects\Values
 */
class DateValueTest extends TestCase
{
    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testConstruct(): void
    {
        $valueObject = new DateValue('2010-01-01');
        $this->assertEquals(new \DateTime('2010-01-01'), $valueObject->getValue());
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testJson(): void
    {
        $valueObject = new DateValue('2010-01-01');
        $this->assertSame('"2010-01-01"', json_encode($valueObject));
    }

    /**
     * @param $input
     * @dataProvider invalidValueProvider
     * @expectedException \Runn\Validation\Exceptions\InvalidDate
     * @throws \Runn\Validation\ValidationError
     */
    public function testInvalidValue($input): void
    {
        new DateValue($input);
    }

    /**
     * @param $input
     * @dataProvider emptyDateProvider
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     * @throws \Runn\Validation\ValidationError
     */
    public function testEmptyValue($input): void
    {
        new DateValue($input);
    }

    /**
     * @return array
     */
    public function invalidValueProvider(): array
    {
        return [
            'boolean true' => [true],
            'boolean false' => [false],
            'integer' => [42],
            'invalid string' => ['2010-XX-YY'],
            'array' => [[1, 2, 3]],
            'resource' => [fopen('php://input', 'rb')],
            'object' => [
                new class
                {
                    //
                }
            ],
        ];
    }

    /**
     * @return array
     */
    public function emptyDateProvider(): array
    {
        return [
            'null' => [null],
            'empty string' => [''],
        ];
    }
}
