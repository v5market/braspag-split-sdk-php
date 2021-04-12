<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Validation\Validator;

final class EmailTest extends TestCase
{
    /**
     * @dataProvider dataProviderIsValid
     */
    public function testIsValid($arg)
    {
        $result = Validator::email()->validator($arg);
        $this->assertTrue($result);
    }

    /**
     * @dataProvider dataProviderIsInvalid
     */
    public function testIsInvalid($arg)
    {
        $result = Validator::email()->validator($arg);
        $this->assertFalse($result);
    }

    public function dataProviderIsValid()
    {
        return [
        ['example@example.com'],
        ['exemplo@exemplo.com.br'],
        ['contact.admin@webmaster.com'],
        ['joe_._test@test.dev']
        ];
    }

    public function dataProviderIsInvalid()
    {
        return [
        [''],
        ['example.example.com'],
        ['exemplo@exemplo.com.'],
        ['test(example).com']
        ];
    }
}
