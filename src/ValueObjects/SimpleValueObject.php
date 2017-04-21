<?php

namespace Runn\ValueObjects;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\PassThruSanitizer;
use Runn\Validation\ValidationError;
use Runn\Validation\Validator;
use Runn\Validation\Validators\PassThruValidator;

/**
 * Abstract simple Value Object
 *
 * Class SimpleValueObject
 * @package Runn\ValueObjects
 */
abstract class SimpleValueObject
    extends SimpleValue
    implements ValueObjectInterface
{

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
     * @throws \Runn\Validation\ValidationError
     */
    public function __construct($value = null, Validator $validator = null, Sanitizer $sanitizer = null)
    {
        $this->validator = $validator ?: $this->getDefaultValidator();
        $this->sanitizer = $sanitizer ?: $this->getDefaultSanitizer();

        $success = $this->validator->validate($value);
        if (!$success) {
            throw new ValidationError($value, 'Value object validation error');
        }

        parent::__construct($this->sanitizer->sanitize($value));
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
     * @param \Runn\ValueObjects\ValueObjectInterface $value
     * @return bool
     */
    public function isEqual(ValueObjectInterface $value): bool
    {
        return (get_class($value) === get_class($this)) && ($value->getValue() === $this->getValue());
    }

}