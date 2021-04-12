<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Validation\Validator;

final class Base64Test extends TestCase
{
    public function testIsValid()
    {
        $arg = file_get_contents(__DIR__ . '/../Data/base64_valid.txt');

        $result = Validator::base64()->validator($arg);
        $this->assertTrue($result);
    }

    public function testIsInvalid()
    {
        $arg = file_get_contents(__DIR__ . '/../Data/base64_invalid.txt');

        $result = Validator::base64()->validator($arg);
        $this->assertFalse($result);
    }
}
