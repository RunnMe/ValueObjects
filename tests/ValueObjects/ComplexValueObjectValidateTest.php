<?php


namespace Runn\tests\ValueObjects\ComplexValueObject;

use Runn\Core\Exception;
use Runn\Core\Exceptions;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Errors\ComplexValueObjectErrors;
use Runn\ValueObjects\Errors\EmptyFieldClass;
use Runn\ValueObjects\Errors\InvalidComplexValue;
use Runn\ValueObjects\Errors\InvalidField;
use Runn\ValueObjects\Errors\InvalidFieldClass;
use Runn\ValueObjects\Errors\InvalidFieldValue;
use Runn\ValueObjects\Errors\MissingField;
use Runn\ValueObjects\Values\BooleanValue;
use Runn\ValueObjects\Values\IntValue;

class CustomComplexValueObjectErrors extends ComplexValueObjectErrors {}
class CustomInvalidFieldError extends InvalidField {}
class CustomEmptyFieldClassError extends EmptyFieldClass {}
class CustomInvalidFieldClassError extends InvalidFieldClass {}
class CustomInvalidFieldValueError extends InvalidFieldValue {}
class CustomMissingField extends MissingField {}

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

    public function testCustomErrorsCollectionWithSkippedFields()
    {
        try {

            $object = new class([
                'foo' => 'bar',
                'first' => 1,
                'second' => 2,
                'third' => [],
            ]) extends ComplexValueObject {
                /** @7.1 */
                protected const ERRORS = [
                    'COLLECTION' => CustomComplexValueObjectErrors::class,
                    'INVALID_FIELD' => CustomInvalidFieldError::class,
                    'EMPTY_FIELD_CLASS' => CustomEmptyFieldClassError::class,
                    'INVALID_FIELD_CLASS' => CustomInvalidFieldClassError::class,
                    'INVALID_FIELD_VALUE' => CustomInvalidFieldValueError::class,
                    'MISSING_FIELD' => CustomMissingField::class,
                ];
                protected static $schema = [
                    'first' => ['class' => null],
                    'second' => ['class' => \stdClass::class],
                    'third' => ['class' => BooleanValue::class],
                ];
            };

        } catch (ComplexValueObjectErrors $errors) {

            $this->assertCount(6, $errors);

            $this->assertInstanceOf(CustomComplexValueObjectErrors::class, $errors);
            $this->assertInstanceOf(ComplexValueObjectErrors::class, $errors);


            $this->assertInstanceOf(CustomEmptyFieldClassError::class, $errors[0]);
            $this->assertSame('first', $errors[0]->getField());

            $this->assertInstanceOf(CustomInvalidFieldClassError::class, $errors[1]);
            $this->assertSame('second', $errors[1]->getField());

            $this->assertInstanceOf(CustomInvalidFieldValueError::class, $errors[2]);
            $this->assertSame('third', $errors[2]->getField());

            $this->assertInstanceOf(CustomMissingField::class, $errors[3]);
            $this->assertSame('first', $errors[3]->getField());

            $this->assertInstanceOf(CustomMissingField::class, $errors[4]);
            $this->assertSame('second', $errors[4]->getField());

            $this->assertInstanceOf(CustomMissingField::class, $errors[5]);
            $this->assertSame('third', $errors[5]->getField());

            return;

        }
        $this->fail();
    }

    public function testCustomErrorsCollectionWithNotSkippedFields()
    {
        try {

            $object = new class([
                'foo' => 'bar',
                'first' => 1,
                'second' => 2,
                'third' => [],
            ]) extends ComplexValueObject {
                /** @7.1 */
                /*protected */const ERRORS = [
                    'COLLECTION' => CustomComplexValueObjectErrors::class,
                    'INVALID_FIELD' => CustomInvalidFieldError::class,
                    'EMPTY_FIELD_CLASS' => CustomEmptyFieldClassError::class,
                    'INVALID_FIELD_CLASS' => CustomInvalidFieldClassError::class,
                    'INVALID_FIELD_VALUE' => CustomInvalidFieldValueError::class,
                    'MISSING_FIELD' => CustomMissingField::class,
                ];
                /** @7.1 */
                protected const SKIP_EXCESS_FIELDS = false;
                protected static $schema = [
                    'first' => ['class' => null],
                    'second' => ['class' => \stdClass::class],
                    'third' => ['class' => BooleanValue::class],
                ];
            };

        } catch (ComplexValueObjectErrors $errors) {

            $this->assertCount(7, $errors);

            $this->assertInstanceOf(CustomComplexValueObjectErrors::class, $errors);
            $this->assertInstanceOf(ComplexValueObjectErrors::class, $errors);

            $this->assertInstanceOf(CustomInvalidFieldError::class, $errors[0]);
            $this->assertSame('foo', $errors[0]->getField());

            $this->assertInstanceOf(CustomEmptyFieldClassError::class, $errors[1]);
            $this->assertSame('first', $errors[1]->getField());

            $this->assertInstanceOf(CustomInvalidFieldClassError::class, $errors[2]);
            $this->assertSame('second', $errors[2]->getField());

            $this->assertInstanceOf(CustomInvalidFieldValueError::class, $errors[3]);
            $this->assertSame('third', $errors[3]->getField());

            $this->assertInstanceOf(CustomMissingField::class, $errors[4]);
            $this->assertSame('first', $errors[4]->getField());

            $this->assertInstanceOf(CustomMissingField::class, $errors[5]);
            $this->assertSame('second', $errors[5]->getField());

            $this->assertInstanceOf(CustomMissingField::class, $errors[6]);
            $this->assertSame('third', $errors[6]->getField());

            return;

        }
        $this->fail();
    }

}