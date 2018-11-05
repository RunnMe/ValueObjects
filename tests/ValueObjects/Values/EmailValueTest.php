<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\SingleValueObject;
use Runn\ValueObjects\Values\EmailValue;
use Runn\ValueObjects\Values\StringValue;

/**
 * Class EmailValueTest
 * @package Tests\ValueObjects\Values
 */
class EmailValueTest extends TestCase
{
    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testConstruct(): void
    {
        $valueObject = new EmailValue('foo@bar.baz');

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(StringValue::class, $valueObject);
        $this->assertInstanceOf(EmailValue::class, $valueObject);

        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('foo@bar.baz', $valueObject->getValue());
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testValidObject(): void
    {
        $valueObject = new EmailValue(
            new class
            {
                public function __toString()
                {
                    return 'foo@bar.baz';
                }
            }
        );
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('foo@bar.baz', $valueObject->getValue());
    }

    /**
     * @param $input
     * @dataProvider emptyValueProvider
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     * @throws \Runn\Validation\ValidationError
     */
    public function testEmptyValue($input): void
    {
        new EmailValue($input);
    }

    /**
     * @param $input
     * @dataProvider invalidValueProvider
     * @expectedException \Runn\Validation\Exceptions\InvalidEmail
     * @throws \Runn\Validation\ValidationError
     */
    public function testResource($input): void
    {
        new EmailValue($input);
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
