<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\SingleValueObject;
use Runn\ValueObjects\Values\UuidValue;

/**
 * Class UuidValueTest
 * @package Tests\ValueObjects\Values
 */
class UuidValueTest extends TestCase
{
    /**
     * @param $input
     * @dataProvider invalidValueProvider
     * @expectedException \Runn\Validation\Exceptions\InvalidUuid
     * @throws \Runn\Validation\ValidationError
     */
    public function testInvalidValue($input): void
    {
        new UuidValue($input);
    }
    /**
     * @param $input
     * @dataProvider emptyValueProvider
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     * @throws \Runn\Validation\ValidationError
     */
    public function testEmptyValue($input): void
    {
        new UuidValue($input);
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testConstruct(): void
    {
        $valueObject = new UuidValue('e3b9876f-86e4-4895-8648-1b6ee8091786');

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(UuidValue::class, $valueObject);

        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('{E3B9876F-86E4-4895-8648-1B6EE8091786}', $valueObject->getValue());
    }

    /**
     * @return array
     */
    public function invalidValueProvider(): array
    {
        return [
            'int' => [42],
            'float' => [1.23],
            'true' => [true],
            'not empty string' => ['blah-blah-blah'],
            'array' => [[1, 2, 3]],
            'object' => [
                new class
                {
                    //
                }
            ],
            'resource' => [fopen('php://input', 'rb')],
        ];
    }

    /**
     * @return array
     */
    public function emptyValueProvider(): array
    {
        return [
            'null' => [null],
            'false' => [false],
            'empty string' => [''],
        ];
    }
}
