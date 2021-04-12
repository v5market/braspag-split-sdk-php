<?php

namespace Braspag\Split\Validation\Rule;

abstract class AbstractRule
{
    /**
     * Raliza a validação da regra
     *
     * @return boolean
     */
    abstract public function validator($input);
}
