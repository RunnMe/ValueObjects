<?php

namespace Runn\tests\ValueObjects\ComplexValueObject;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Errors\ComplexValueObjectErrors;
use Runn\ValueObjects\Errors\EmptyFieldClass;
use Runn\ValueObjects\Errors\InvalidFieldClass;
use Runn\ValueObjects\Errors\InvalidFieldKey;
use Runn\ValueObjects\Errors\InvalidFieldValue;
use Runn\ValueObjects\Errors\MissingField;
use Runn\ValueObjects\Exception;

class InvalidExceptions11 extends ComplexValueObject {
    protected const ERRORS = [
    ];
}
class InvalidExceptions12 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => \stdClass::class,
    ];
}

class InvalidExceptions21 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
    ];
}
class InvalidExceptions22 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
        'INVALID_FIELD_KEY' => \stdClass::class,
    ];
}

class InvalidExceptions31 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
        'INVALID_FIELD_KEY' => InvalidFieldKey::class,
    ];
}
class InvalidExceptions32 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
        'INVALID_FIELD_KEY' => InvalidFieldKey::class,
        'EMPTY_FIELD_CLASS' => \stdClass::class,
    ];
}

class InvalidExceptions41 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
        'INVALID_FIELD_KEY' => InvalidFieldKey::class,
        'EMPTY_FIELD_CLASS' => EmptyFieldClass::class,
    ];
}
class InvalidExceptions42 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
        'INVALID_FIELD_KEY' => InvalidFieldKey::class,
        'EMPTY_FIELD_CLASS' => EmptyFieldClass::class,
        'INVALID_FIELD_CLASS' => \stdClass::class,
    ];
}

class InvalidExceptions51 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
        'INVALID_FIELD_KEY' => InvalidFieldKey::class,
        'EMPTY_FIELD_CLASS' => EmptyFieldClass::class,
        'INVALID_FIELD_CLASS' => InvalidFieldClass::class,
    ];
}
class InvalidExceptions52 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
        'INVALID_FIELD_KEY' => InvalidFieldKey::class,
        'EMPTY_FIELD_CLASS' => EmptyFieldClass::class,
        'INVALID_FIELD_CLASS' => InvalidFieldClass::class,
        'INVALID_FIELD_VALUE' => \stdClass::class,
    ];
}

class InvalidExceptions61 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
        'INVALID_FIELD_KEY' => InvalidFieldKey::class,
        'EMPTY_FIELD_CLASS' => EmptyFieldClass::class,
        'INVALID_FIELD_CLASS' => InvalidFieldClass::class,
        'INVALID_FIELD_VALUE' => InvalidFieldValue::class,
    ];
}
class InvalidExceptions62 extends ComplexValueObject {
    protected const ERRORS = [
        'COLLECTION' => ComplexValueObjectErrors::class,
        'INVALID_FIELD_KEY' => InvalidFieldKey::class,
        'EMPTY_FIELD_CLASS' => EmptyFieldClass::class,
        'INVALID_FIELD_CLASS' => InvalidFieldClass::class,
        'INVALID_FIELD_VALUE' => InvalidFieldValue::class,
        'MISSING_FIELD' => \stdClass::class,
    ];
}

class ComplexValueObjectCheckExceptionsTest extends TestCase
{

    public function testEmptyErrorsCollectionClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions11::class . "::ERRORS['COLLECTION'] must be Runn\ValueObjects\Errors\ComplexValueObjectErrors or extends it");
        $obj = new InvalidExceptions11();
    }

    public function testInvalidErrorsCollectionClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions12::class . "::ERRORS['COLLECTION'] must be Runn\ValueObjects\Errors\ComplexValueObjectErrors or extends it");
        $obj = new InvalidExceptions12();
    }

    public function testEmptyErrorsInvalidFieldKeyClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions21::class . "::ERRORS['INVALID_FIELD_KEY'] must be Runn\ValueObjects\Errors\InvalidFieldKey or extends it");
        $obj = new InvalidExceptions21();
    }

    public function testInvalidErrorsInvalidFieldKeyClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions22::class . "::ERRORS['INVALID_FIELD_KEY'] must be Runn\ValueObjects\Errors\InvalidFieldKey or extends it");
        $obj = new InvalidExceptions22();
    }

    public function testEmptyErrorsEmptyFieldClassClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions31::class . "::ERRORS['EMPTY_FIELD_CLASS'] must be Runn\ValueObjects\Errors\EmptyFieldClass or extends it");
        $obj = new InvalidExceptions31();
    }

    public function testInvalidErrorsEmptyFieldClassClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions32::class . "::ERRORS['EMPTY_FIELD_CLASS'] must be Runn\ValueObjects\Errors\EmptyFieldClass or extends it");
        $obj = new InvalidExceptions32();
    }

    public function testEmptyErrorsInvalidFieldClassClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions41::class . "::ERRORS['INVALID_FIELD_CLASS'] must be Runn\ValueObjects\Errors\InvalidFieldClass or extends it");
        $obj = new InvalidExceptions41();
    }

    public function testInvalidErrorsInvalidFieldClassClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions42::class . "::ERRORS['INVALID_FIELD_CLASS'] must be Runn\ValueObjects\Errors\InvalidFieldClass or extends it");
        $obj = new InvalidExceptions42();
    }

    public function testEmptyErrorsInvalidFieldValueClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions51::class . "::ERRORS['INVALID_FIELD_VALUE'] must be Runn\ValueObjects\Errors\InvalidFieldValue or extends it");
        $obj = new InvalidExceptions51();
    }

    public function testInvalidErrorsInvalidFieldValueClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions52::class . "::ERRORS['INVALID_FIELD_VALUE'] must be Runn\ValueObjects\Errors\InvalidFieldValue or extends it");
        $obj = new InvalidExceptions52();
    }

    public function testEmptyErrorsMissingFieldClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions61::class . "::ERRORS['MISSING_FIELD'] must be Runn\ValueObjects\Errors\MissingField or extends it");
        $obj = new InvalidExceptions61();
    }

    public function testInvalidErrorsMissingFieldClass()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Class " . InvalidExceptions62::class . "::ERRORS['MISSING_FIELD'] must be Runn\ValueObjects\Errors\MissingField or extends it");
        $obj = new InvalidExceptions62();
    }

}
