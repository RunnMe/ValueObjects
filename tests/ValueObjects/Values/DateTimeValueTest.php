<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\Values\DateTimeValue;

/**
 * Class DateTimeValueTest
 * @package Tests\ValueObjects\Values
 */
class DateTimeValueTest extends TestCase
{
    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testConstruct(): void
    {
        $valueObject = new DateTimeValue('2010-01-01');
        $this->assertEquals(new \DateTime('2010-01-01'), $valueObject->getValue());

        $valueObject = new DateTimeValue('2010-01-01 12:34:56');
        $this->assertEquals(new \DateTime('2010-01-01 12:34:56'), $valueObject->getValue());
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testJson(): void
    {
        $valueObject = new DateTimeValue('2010-01-01');
        $this->assertSame('"2010-01-01T00:00:00' . date('P') . '"', json_encode($valueObject));

        $valueObject = new DateTimeValue('2010-01-01 12:34:56');
        $this->assertSame('"2010-01-01T12:34:56' . date('P') . '"', json_encode($valueObject));
    }

    /**
     * @param $input
     * @dataProvider emptyValueProvider
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     * @throws \Runn\Validation\ValidationError
     */
    public function testEmptyValue($input): void
    {
        new DateTimeValue($input);
    }

    /**
     * @param $input
     * @dataProvider invalidValueProvider
     * @expectedException \Runn\Validation\Exceptions\InvalidDateTime
     * @throws \Runn\Validation\ValidationError
     */
    public function testInvalidValue($input): void
    {
        new DateTimeValue($input);
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
    public function emptyValueProvider(): array
    {
        return [
            'null' => [null],
            'empty string' => [''],
        ];
    }
}
