<?php

namespace Braspag\Split\Validation;

class Validator
{
    public static function __callStatic($rule, $args)
    {
        return Factory::getDefaultInstance()->rule($rule, $args);
    }
}
