<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Subordinate\MerchantDiscountRate;
use \Braspag\Split\Constants\Card\Type as CardType;
use \Braspag\Split\Constants\Card\Brand as CardBrand;

class MerchantDiscountRateTest extends TestCase
{
    /**
     * @group ClassSubordinateMerchantDiscountRate
     */
    public function testCreateInstance()
    {
        $instance = new MerchantDiscountRate;
        $this->assertInstanceOf(MerchantDiscountRate::class, $instance);
    }

    /**
     * @group ClassSubordinateMerchantDiscountRate
     */
    public function testSetterCardWithValidArguments()
    {
        $instance = new MerchantDiscountRate;
        $instance->setCard(CardType::CREDIT, CardBrand::VISA);

        $vType = "CreditCard" === $instance->getCardType();
        $vBrand = "Visa" === $instance->getCardBrand();

        $this->assertTrue($vType && $vBrand);
    }

    /**
     * @group ClassSubordinateMerchantDiscountRate
     */
    public function testSetterCardWithInvalidArgumentsMustHaveErrors()
    {
        $this->expectException(OutOfBoundsException::class);
        $instance = new MerchantDiscountRate;
        $instance->setCard(CardType::CREDIT, 'UNKNOWN');
    }

    /**
     * @dataProvider providerCardTypeValid
     * @group ClassSubordinateMerchantDiscountRate
     */
    public function testCardTypeWithArgumentsValid($arg, $expected)
    {
        $instance = new MerchantDiscountRate;
        $instance->setCardType($arg);

        $this->assertEquals($expected, $instance->getCardType());
    }

    /**
     * @dataProvider providerCardTypeInvalid
     * @group ClassSubordinateMerchantDiscountRate
     */
    public function testCardTypeWithArgumentsInvalid($arg)
    {
        $this->expectException(OutOfBoundsException::class);
        $instance = new MerchantDiscountRate;
        $instance->setCardType($arg);
    }

    /**
     * @dataProvider providerCardBrandValid
     * @group ClassSubordinateMerchantDiscountRate
     */
    public function testCardBrandWithArgumentsValid($arg, $expected)
    {
        $instance = new MerchantDiscountRate;
        $instance->setCardBrand($arg);

        $this->assertEqualsIgnoringCase($expected, $instance->getCardBrand());
    }

    /**
     * @dataProvider providerCardBrandInvalid
     * @group ClassSubordinateMerchantDiscountRate
     */
    public function testCardBrandWithArgumentsInvalid($arg)
    {
        $this->expectException(OutOfBoundsException::class);
        $instance = new MerchantDiscountRate;
        $instance->setCardBrand($arg);
    }

    /**
     * @group ClassSubordinateMerchantDiscountRate
     */
    public function testSetterInstallmentWithValidArguments()
    {
        $instance = new MerchantDiscountRate;
        $instance->setInstallment(1, 12, 0.75);

        $vInitial = 1 == $instance->getInitialInstallment();
        $vFinal   = 12 == $instance->getFinalInstallment();
        $vPercent = 0.75 == $instance->getPercent();

        $this->assertTrue($vInitial && $vFinal && $vPercent);
    }

    /**
     * @group ClassSubordinateMerchantDiscountRate
     */
    public function testSetterInstallmentWithInvalidArgumentsMustHaveErrors()
    {
        $this->expectException(OutOfRangeException::class);
        $instance = new MerchantDiscountRate;
        $instance->setInstallment(1, 13, 0.75);
    }

    public function providerCardTypeValid()
    {
        return [
        [CardType::CREDIT, CardType::CREDIT],
        [CardType::DEBIT, CardType::DEBIT],
        ['CreditCard', CardType::CREDIT],
        ['DebitCard', CardType::DEBIT]
        ];
    }

    public function providerCardTypeInvalid()
    {
        return [
        ['CreditCard_'],
        ['DebitCrd'],
        ['DebitCard ']
        ];
    }

    public function providerCardBrandValid()
    {
        return [
        [CardBrand::VISA, 'Visa'],
        [CardBrand::MASTER, 'Master'],
        [CardBrand::AMEX, 'Amex'],
        [CardBrand::ELO, 'Elo'],
        [CardBrand::DINERS, 'Diners'],
        [CardBrand::DISCOVER, 'Discover'],
        [CardBrand::HIPERCARD, 'Hipercard'],
        [CardBrand::HIPERCARD, 'HiperCard'],
        [CardBrand::MAESTRO, 'Maestro'],
        [CardBrand::MAESTRO, 'maestro']
        ];
    }

    public function providerCardBrandInvalid()
    {
        return [
        [' Visa'],
        ['Master '],
        [' Amex '],
        ['El0'],
        ['Dlners']
        ];
    }
}
