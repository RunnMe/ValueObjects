7.0.5, 7.1.5, 7.2.5
===================
* new `ComplexValueObject::innerGet()` behavior: Simple Value Objects values are returned instead of objects
* `ComplexValueObject::SKIP_EXCESS_FIELDS` constant is added

7.0.4, 7.1.4, 7.2.4
===================
* `ValueObjectInterface::__toString()` method is added. 
* `ComplexValueObject` and `Entity` classes improvements
* `DateTimeValue` and `DateValue` classes `getValue()` method returns `DateTime` object now
* Refinement of the concepts of "equality" and "equivalence" for `Entity`
* License identifier update

7.0.3, 7.1.3, 7.2.3
===================
* `ComplexValueObjectErrors` class is added. It collects all `ComplexValueObject` construct errors.
* `ComplexValueObject::validate()` method is added for complex value post-validation
* `ComplexValueObject::jsonSerialize()` method minor fix
* `ComplexValueObject::ERRORS` constant containing custom error class names is added 

7.0.2, 7.1.2, 7.2.2
===================
* `EmailValue` class is added
* `UuidValue` class is added
* `Entity` class development

7.0.1, 7.1.1, 7.2.1
===================
* Some refactoring and improvements
* `ComplexValueObjects` are immutable now, `Entities` non-primary fields are still mutable

7.0.0, 7.1.0, 7.2.0
===================
* First released version. Code is transfered from Running.FM project