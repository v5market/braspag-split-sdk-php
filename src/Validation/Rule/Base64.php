<?php

namespace Braspag\Split\Validation\Rule;

/**
 * @link https://github.com/Respect/Validation/blob/master/library/Rules/Base64.php
 */
class Base64 extends AbstractRule
{
    /**
     * {@inheritDoc}
     */
    public function validator($input)
    {
        if (!is_string($input)) {
            return false;
        }

        if (!preg_match('#^[A-Za-z0-9+/\n\r]+={0,2}$#', $input)) {
            return false;
        }

        return strlen($input) % 4 === 0;
    }
}
