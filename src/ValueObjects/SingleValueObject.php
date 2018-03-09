<?php

namespace Runn\ValueObjects;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\PassThruSanitizer;
use Runn\Validation\ValidationError;
use Runn\Validation\Validator;
use Runn\Validation\Validators\PassThruValidator;

/**
 * Abstract single Value Object
 *
 * Class SingleValueObject
 * @package Runn\ValueObjects
 */
abstract class SingleValueObject
    implements ValueObjectInterface, \JsonSerializable
{

    use ValueObjectTrait;

    /**
     * @var \Runn\Validation\Validator
     */
    protected $validator;

    /**
     * @var \Runn\Sanitization\Sanitizer
     */
    protected $sanitizer;

    /**
     * SimpleValueObject constructor.
     * @param $value
     * @param \Runn\Validation\Validator|null $validator
     * @param \Runn\Sanitization\Sanitizer|null $sanitizer
     * @throws ValidationError
     */
    public function __construct($value = null, Validator $validator = null, Sanitizer $sanitizer = null)
    {
        $this->validator = $validator ?? $this->getDefaultValidator();
        $this->sanitizer = $sanitizer ?? $this->getDefaultSanitizer();
        $this->setValue($value);
    }

    /**
     * @param mixed $value
     * @return $this
     * @throws \Runn\Validation\ValidationError
     */
    protected function setValue($value)
    {
        $success = $this->validator->validate($value);
        if (!$success) {
            throw new ValidationError($value, 'Value object validation error');
        }
        $this->__value = $this->sanitizer->sanitize($value);
        return $this;
    }

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new PassThruValidator();
    }

    /**
     * @return \Runn\Sanitization\Sanitizer
     */
    protected function getDefaultSanitizer(): Sanitizer
    {
        return new PassThruSanitizer();
    }

    /**
     * JsonSerializable implementation
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }

}
