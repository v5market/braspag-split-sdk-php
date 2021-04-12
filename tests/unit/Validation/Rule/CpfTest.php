<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Validation\Validator;

final class CpfTest extends TestCase
{
    /**
     * @dataProvider dataProviderIsValid
     */
    public function testIsValid($arg)
    {
        $result = Validator::cpf()->validator($arg);
        $this->assertTrue($result);
    }

    /**
     * @dataProvider dataProviderIsInvalid
     */
    public function testIsInvalid($arg)
    {
        $result = Validator::cpf()->validator($arg);
        $this->assertFalse($result);
    }

    public function dataProviderIsValid()
    {
        return [
        ['342.444.198-88'],
        ['342.444.198.88'],
        ['350.45261819'],
        ['693-319-118-40'],
        ['3.6.8.8.9.2.5.5.4.8.8'],
        ['11598647644'],
        ['86734718697'],
        ['86223423284'],
        ['24845408333'],
        ['95574461102'],
        ];
    }

    public function dataProviderIsInvalid()
    {
        return [
        [''],
        ['01234567890'],
        ['000.000.000-00'],
        ['111.222.444-05'],
        ['999999999.99'],
        ['8.8.8.8.8.8.8.8.8.8.8'],
        ['693-319-110-40'],
        ['698.111-111.00'],
        ['11111111111'],
        ['22222222222'],
        ['12345678900'],
        ['99299929384'],
        ['84434895894'],
        ['44242340000'],
        ['1'],
        ['22'],
        ['123'],
        ['992999999999929384'],
        ];
    }
}
