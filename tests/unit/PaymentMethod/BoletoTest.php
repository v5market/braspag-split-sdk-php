<?php

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\PaymentMethod\Boleto;

class BoletoTest extends TestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Boleto();
        $this->assertInstanceOf(Boleto::class, $instance);
    }

    /**
     * @test
     */
    public function invalidIdentificationShouldGiveError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Boleto();
        $instance->setIdentification('00.000.000/0001-90');
    }

    /**
     * @test
     */
    public function expirationDateLessThanTheCurrentDateShouldReturnError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Boleto();
        $instance->setExpirationDate(new DateTime('13-07-1993'));
    }

    /**
     * @test
     */
    public function convertObjectToJsonWithoutErrors()
    {
        $json = '
        {
            "BoletoNumber":"2017091101",
            "Assignor": "Empresa Teste",
            "Demonstrative": "Desmonstrative Teste",
            "ExpirationDate": "2030-12-31",
            "Identification": "00000000000191",
            "Instructions": "Aceitar somente até a data de vencimento.",
            "DaysToFine": 1,
            "FineRate": 10.00000,
            "FineAmount": 1000,
            "DaysToInterest": 1,
            "InterestRate": 5.00000,
            "InterestAmount": 500,
            "NullifyDays": 9
        }
        ';

        $instance = new Boleto();
        $instance->setBoletoNumber('2017091101');
        $instance->setAssignor('Empresa Teste');
        $instance->setDemonstrative('Desmonstrative Teste');
        $instance->setExpirationDate(DateTime::createFromFormat('Y-m-d', '2030-12-31'));
        $instance->setIdentification('00.000.000/0001-91');
        $instance->setInstructions('Aceitar somente até a data de vencimento.');
        $instance->setDaysToFine(1);
        $instance->setFineRate(10.00000);
        $instance->setFineAmount(1000);
        $instance->setDaysToInterest(1);
        $instance->setInterestRate(5.00000);
        $instance->setInterestAmount(500);
        $instance->setNullifyDays(9);

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function convertJsonToObjectWithoutErrors()
    {
        $json = '
        {
            "BoletoNumber":"2017091101",
            "Assignor": "Empresa Teste",
            "Demonstrative": "Desmonstrative Teste",
            "ExpirationDate": "2030-12-31",
            "Identification": "00000000000191",
            "Instructions": "Aceitar somente até a data de vencimento.",
            "DaysToFine": 1,
            "FineRate": 10.00000,
            "FineAmount": 1000,
            "DaysToInterest": 1,
            "InterestRate": 5.00000,
            "InterestAmount": 500,
            "NullifyDays": 9
            }
        ';

        $instance = new Boleto();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
