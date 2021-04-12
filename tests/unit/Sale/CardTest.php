<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Constants\Card\Brand as CardBrand;
use \Braspag\Split\Constants\Card\Type as CardType;
use \Braspag\Split\Domains\PaymentMethod\CreditCard;
use \Braspag\Split\Domains\PaymentMethod\DebitCard;

class CardTest extends TestCase
{
    /**
     * @group ClassCard
     */
    public function testCreateInstance()
    {
        $instance = new CreditCard;
        $this->assertInstanceOf(CreditCard::class, $instance);
    }

    /**
     * @group ClassCard
     * @dataProvider providerCardNumberValid
     */
    public function testCardNumberWithValidArgument($arg)
    {
        $instance = new CreditCard;
        $instance->setCardNumber($arg);

        $expected = preg_replace('/\D/', '', $arg);

        $this->assertEquals($expected, $instance->getCardNumber());
    }

    /**
     * @group ClassCard
     */
    public function testHolderWithValidArgument()
    {
        $instance = new CreditCard;
        $instance->setHolder('Joe John');

        $this->assertEquals('Joe John', $instance->getHolder());
    }

    /**
     * @group ClassCard
     * @dataProvider providerExpirationDateValid
     */
    public function testExpirationDateWithValidArguments($arg)
    {
        $instance = new CreditCard;
        $instance->setExpirationDate($arg);

        $this->assertEquals($arg, $instance->getExpirationDate());
    }

    /**
     * @group ClassCard
     */
    public function testSecurityCodeWithValidArguments()
    {
        $instance = new CreditCard;
        $instance->setSecurityCode('123');

        $this->assertEquals('123', $instance->getSecurityCode());
    }

    /**
     * @group ClassCard
     */
    public function testBrandWithValidArgument()
    {
        $instance = new CreditCard;
        $instance->setBrand('Visa');

        $this->assertEquals('Visa', $instance->getBrand());
    }

    /**
     * @group ClassCard
     */
    public function testSetterSaveCardMustConvertToBooleano()
    {
        $instance = new CreditCard;
        $instance->setSaveCard('no');

        $this->assertTrue($instance->getSaveCard());
    }

    /**
     * @group ClassCard
     */
    public function testSetterAliasWithValidArgument()
    {
        $instance = new CreditCard;
        $instance->setAlias('my_credit_card');

        $this->assertEquals('my_credit_card', $instance->getAlias());
    }

    /**
     * @group ClassCard
     */
    public function testsSetterWithLongerTextThanAllowedShouldGiveAnError()
    {
        $this->expectException(LengthException::class);
        $value = str_repeat('v', 65);
        $instance = new CreditCard;
        $instance->setAlias($value);
    }

    /**
     * @group ClassCard
     */
    public function testNewInstanceOfDebitCard()
    {
        $instance = new DebitCard;
        $this->assertFalse($instance->isCreditCard());

        $instance = new CreditCard;
        $this->assertTrue($instance->isCreditCard());
    }

    /**
     * @group ClassCard
     */
    public function testWhetherToarrayReturnsDataAccordingToWhatTheApiRequests()
    {
        $expected = [
        "CardNumber" => "4111111111111111",
        "Holder" => "Nome do Portador",
        "ExpirationDate" => "12/2021",
        "SecurityCode" => "123",
        "Brand" => "Visa",
        "SaveCard" => false
        ];

        $card = new CreditCard;
        $card->setCardNumber('4111111111111111');
        $card->setHolder('Nome do Portador');
        $card->setExpirationDate('12/2021');
        $card->setSecurityCode('123');
        $card->setBrand('Visa');
        $card->setSaveCard(false);

        $this->assertEquals($expected, $card->toArray());
    }

    /**
     * @group ClassCard
     */
    public function testPopulateMethodShouldHaveNoErrors()
    {
        $json = '{"CardNumber":"455187******0181","Holder":"Nome do Portador","ExpirationDate":"12\/2021","SecurityCode":"123","Brand":"Visa","SaveCard":false}';

        $card = new DebitCard;
        $card->populate(json_decode($json));

        $this->assertEquals($json, json_encode($card));
    }

    /**
     * @group ClassCard
     * @dataProvider providerBrandsName
     */
    public function testWhetherTheCardsFlagNameIsCorrectlyReturned($value, $expected)
    {
        $this->assertEquals(CardBrand::get($value), $expected);
    }

    /**
     * @group ClassCard
     * @dataProvider providerTypeName
     */
    public function testWhetherTheCardsTypeNameIsCorrectlyReturned($value, $expected)
    {
        $this->assertEquals(CardType::get($value), $expected);
    }

    /**
     * @link https://suporte.braspag.com.br/hc/pt-br/articles/360028728212-Cart%C3%B5es-para-teste-Rede
     */
    public function providerCardNumberValid()
    {
        return [
        ['5277696455399733'],
        ['5448 2800 0000 0007'],
        ['2 2 2 3 0 0 0 1 4 8 4 0 0 0 1 0'],
        [' 2223020000000005 '],
        ['47 6 11 2 0 000 000148'],
        ['4235647728025682'],
        ['6062.8256.2425.4001'],
        ['6370950847866501'],
        ['36490101441625'],
        ['3569990012290937'],
        ['3572000100200142753']
        ];
    }

    public function providerExpirationDateValid()
    {
        return [
        ['01/2020'],
        ['02/2020'],
        ['03/2020'],
        ['05/2020'],
        ['06/2020'],
        ['07/2020'],
        ['08/2020'],
        ['09/2020'],
        ['10/2020'],
        ['11/2020'],
        ['12/2020'],
        ];
    }

    public function providerBrandsName()
    {
        return [
        ['Visa', CardBrand::VISA],
        ['master', CardBrand::MASTER],
        ['aMeX', CardBrand::AMEX],
        ['elo', CardBrand::ELO],
        ['DINERS', CardBrand::DINERS],
        [' Discover ', CardBrand::DISCOVER],
        ['  Hipercard', CardBrand::HIPERCARD],
        ['Maestro   ', CardBrand::MAESTRO],
        ['AuRA', CardBrand::AURA],
        ['jCB', CardBrand::JCB],
        ['invalid', null]
        ];
    }

    public function providerTypeName()
    {
        return [
        ['CreditCard', CardType::CREDIT],
        ['creditcard', CardType::CREDIT],
        ['credit', CardType::CREDIT],
        ['DEBITCARD', CardType::DEBIT],
        [' debitCARD ', CardType::DEBIT],
        [' debitCARD', CardType::DEBIT],
        ['oxê!', null]
        ];
    }
}
