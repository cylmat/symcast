<?php

namespace Forms\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})  // said it can be used on a property, can be CLASS
 */
class MyUniqUser extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $alpha_message = "I think you're already registered with title ***title*** !";
}
