<?php

namespace Braspag\Split\Validation\Rule;

class Email extends AbstractRule
{
    public function validator($input)
    {
        return !!filter_var($input, FILTER_VALIDATE_EMAIL);
    }
}
