<?php


namespace Runn\tests\ValueObjects\ComplexValueObject;

use Runn\Core\Exception;
use Runn\Core\Exceptions;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Errors\ComplexValueObjectErrors;
use Runn\ValueObjects\Errors\InvalidComplexValue;
use Runn\ValueObjects\Values\IntValue;

class CustomComplexValueObjectError extends ComplexValueObjectErrors {}

class ComplexValueObjectValidateTest extends \PHPUnit_Framework_TestCase
{

    public function testTrue()
    {
        $object = new class(['first' => 1, 'second' => 2]) extends ComplexValueObject {
            protected static $schema = [
                'first' => ['class' => IntValue::class],
                'second' => ['class' => IntValue::class],
            ];
            protected function validate()
            {
                return true;
            }
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertSame(['first' => 1, 'second' => 2], $object->getValue());
    }

    public function testFalse()
    {
        try {

            $object = new class(['first' => 1, 'second' => 2]) extends ComplexValueObject {
                protected static $schema = [
                    'first' => ['class' => IntValue::class],
                    'second' => ['class' => IntValue::class],
                ];
                protected function validate()
                {
                    return false;
                }
            };

        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(1, $errors);

            $this->assertInstanceOf(InvalidComplexValue::class, $errors[0]);
            $this->assertSame('Invalid complex value', $errors[0]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testThrowOne()
    {
        try {

            $object = new class(['first' => 1, 'second' => 2]) extends ComplexValueObject {
                protected static $schema = [
                    'first' => ['class' => IntValue::class],
                    'second' => ['class' => IntValue::class],
                ];
                protected function validate()
                {
                    throw new InvalidComplexValue('One exception');
                }
            };

        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(1, $errors);

            $this->assertInstanceOf(InvalidComplexValue::class, $errors[0]);
            $this->assertSame('One exception', $errors[0]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testThrowCollection()
    {
        try {

            $object = new class(['first' => 1, 'second' => 2]) extends ComplexValueObject {
                protected static $schema = [
                    'first' => ['class' => IntValue::class],
                    'second' => ['class' => IntValue::class],
                ];
                protected function validate()
                {
                    throw new Exceptions([
                        new InvalidComplexValue('First exception'),
                        new InvalidComplexValue('Second exception')
                    ]);
                }
            };

        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(2, $errors);

            $this->assertInstanceOf(InvalidComplexValue::class, $errors[0]);
            $this->assertSame('First exception', $errors[0]->getMessage());

            $this->assertInstanceOf(InvalidComplexValue::class, $errors[1]);
            $this->assertSame('Second exception', $errors[1]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testGenerateExceptions()
    {
        try {

            $object = new class(['first' => 1, 'second' => 2]) extends ComplexValueObject {
                protected static $schema = [
                    'first' => ['class' => IntValue::class],
                    'second' => ['class' => IntValue::class],
                ];
                protected function validate()
                {
                    yield new InvalidComplexValue('First exception');
                    yield new InvalidComplexValue('Second exception');
                    yield new Exception('Third exception');
                }
            };

        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(2, $errors);

            $this->assertInstanceOf(InvalidComplexValue::class, $errors[0]);
            $this->assertSame('First exception', $errors[0]->getMessage());

            $this->assertInstanceOf(InvalidComplexValue::class, $errors[1]);
            $this->assertSame('Second exception', $errors[1]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testCustomErrorsCollection()
    {
        try {

            $object = new class(['first' => 1, 'second' => 2]) extends ComplexValueObject {
                /** @7.1 */
                /*protected */const ERRORS = ['COLLECTION' => CustomComplexValueObjectError::class];
                protected static $schema = [
                    'first' => ['class' => IntValue::class],
                    'second' => ['class' => IntValue::class],
                ];
                protected function validate()
                {
                    throw new InvalidComplexValue('First exception');
                }
            };

        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(1, $errors);

            $this->assertInstanceOf(CustomComplexValueObjectError::class, $errors);
            $this->assertInstanceOf(ComplexValueObjectErrors::class, $errors);

            $this->assertInstanceOf(InvalidComplexValue::class, $errors[0]);
            $this->assertSame('First exception', $errors[0]->getMessage());

            return;
        }
        $this->fail();
    }

}