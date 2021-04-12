<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Subordinate\Agreement;
use \Braspag\Split\Domains\Subordinate\MerchantDiscountRate;
use \Braspag\Split\Domains\Environment;
use \Braspag\Split\Constants\Card\Type as CardType;
use \Braspag\Split\Constants\Card\Brand as CardBrand;

class AgreementTest extends TestCase
{
    /**
     * @group ClassSubordinateAgreement
     */
    public function testCreateInstance()
    {
        $instance = new Agreement();
        $this->assertInstanceOf(Agreement::class, $instance);
    }

    /**
     * @group ClassSubordinateAgreement
     */
    public function testFeeWithArgumentsValid()
    {
        $value = 5 * 100;
        $instance = new Agreement();
        $instance->setFee($value);

        $this->assertEquals($value, $instance->getFee());
    }

    /**
     * @group ClassSubordinateAgreement
     */
    public function testMerchantDiscountRateId()
    {
        $expected = [
        "Fee" => 1000,
        "MdrPercentage" => 1.0,
        "MerchantDiscountRateId" => "d09fe9d3-98c7-4c37-9bd3-7c1c91ee15de"
        ];
        $instance = new Agreement;
        $instance->setMerchantDiscountRateId("d09fe9d3-98c7-4c37-9bd3-7c1c91ee15de");
        $instance->setFee(1000);
        $instance->setMdrPercentage(1.0);

        $this->assertEquals($expected, $instance->toArray());
    }

    /**
     * @dataProvider providerMerchantDiscountRatesInvalid
     * @group ClassSubordinateAgreement
     */
    public function testSetterMerchantDiscountRatesWithArgumentsInvalid($arg)
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Agreement;
        $instance->setMerchantDiscountRates($arg);
    }

    /**
     * @group ClassSubordinateAgreement
     */
    public function testWhetherTheToArrayMethodIsReturningCorrectlyMDR()
    {
        $data = [
        "Fee" => 1000,
        "MdrPercentage" => 99.99
        ];

        $agreement = new Agreement;
        $agreement->setFee($data["Fee"]);
        $agreement->setMdrPercentage($data["MdrPercentage"]);

        $this->assertEqualsIgnoringCase($data, $agreement->toArray());
    }

    /**
     * @group ClassSubordinateAgreement
     */
    public function testWhetherTheToArrayMethodIsReturningCorrectlyMultipleDiscountRate()
    {
        $mdr1 = new MerchantDiscountRate();
        $mdr1->setCardType(CardType::CREDIT);
        $mdr1->setCardBrand(CardBrand::VISA);
        $mdr1->setInitialInstallment(1);
        $mdr1->setFinalInstallment(3);
        $mdr1->setPercent(3);

        $mdr2 = new MerchantDiscountRate();
        $mdr2->setCardType(CardType::CREDIT);
        $mdr2->setCardBrand(CardBrand::VISA);
        $mdr2->setInitialInstallment(4);
        $mdr2->setFinalInstallment(6);
        $mdr2->setPercent(6);

        $mdr3 = new MerchantDiscountRate();
        $mdr3->setCardType(CardType::DEBIT);
        $mdr3->setCardBrand(CardBrand::ELO);
        $mdr3->setInitialInstallment(1);
        $mdr3->setFinalInstallment(12);
        $mdr3->setPercent(99);

        $data = [
        "Fee" => 1000,
        "MerchantDiscountRates" => [
            [
            "PaymentArrangement" => [
                "Product" => "CreditCard",
                "Brand" => "Visa"
            ],
            "InitialInstallmentNumber" => 1,
            "FinalInstallmentNumber" => 3,
            "Percent" => 3
            ],
            [
            "PaymentArrangement" => [
                "Product" => "CreditCard",
                "Brand" => "Visa"
            ],
            "InitialInstallmentNumber" => 4,
            "FinalInstallmentNumber" => 6,
            "Percent" => 6
            ],
            [
            "PaymentArrangement" => [
                "Product" => "DebitCard",
                "Brand" => "Elo"
            ],
            "InitialInstallmentNumber" => 1,
            "FinalInstallmentNumber" => 12,
            "Percent" => 99
            ]
        ]
        ];

        $agreement = new Agreement;
        $agreement->setFee($data["Fee"]);
        $agreement->setMerchantDiscountRates([$mdr1, $mdr2, $mdr3]);

        $this->assertEqualsIgnoringCase($data, $agreement->toArray());
    }

    /**
     * @group ClassSubordinateAgreement
     */
    public function testWhetherTheFromJsonMethodIsReturningCorrectlyMultipleDiscountRate()
    {
        $expected = [
        "Fee" => 10,
        "MerchantDiscountRates" => [
            [
            "PaymentArrangement" => [
                "Product" => "DebitCard",
                "Brand" => "Master",
            ],
            "InitialInstallmentNumber" => 1,
            "FinalInstallmentNumber" => 1,
            "Percent" => 1.5,
            ],

            [
            "PaymentArrangement" => [
                "Product" => "CreditCard",
                "Brand" => "Master",
            ],
            "InitialInstallmentNumber" => 1,
            "FinalInstallmentNumber" => 1,
            "Percent" => 2.0,
            ],

            [
            "PaymentArrangement" => [
                "Product" => "CreditCard",
                "Brand" => "Master",
            ],
            "InitialInstallmentNumber" => 2,
            "FinalInstallmentNumber" => 6,
            "Percent" => 3.0,
            ],

            [
            "PaymentArrangement" => [
                "Product" => "CreditCard",
                "Brand" => "Master",
            ],
            "InitialInstallmentNumber" => 7,
            "FinalInstallmentNumber" => 12,
            "Percent" => 4.0,
            ]
        ]
        ];

        $json = json_decode('{
        "Fee": 10,
        "MerchantDiscountRates": [
            {
            "PaymentArrangement": {
                "Product": "DebitCard",
                "Brand": "Master"
            },
            "InitialInstallmentNumber": 1,
            "FinalInstallmentNumber": 1,
            "Percent": 1.5
            },
            {
            "PaymentArrangement": {
                "Product": "CreditCard",
                "Brand": "Master"
            },
            "InitialInstallmentNumber": 1,
            "FinalInstallmentNumber": 1,
            "Percent": 2.0
            },
            {
            "PaymentArrangement": {
                "Product": "CreditCard",
                "Brand": "Master"
            },
            "InitialInstallmentNumber": 2,
            "FinalInstallmentNumber": 6,
            "Percent": 3.0
            },
            {
            "PaymentArrangement": {
                "Product": "CreditCard",
                "Brand": "Master"
            },
            "InitialInstallmentNumber": 7,
            "FinalInstallmentNumber": 12,
            "Percent": 4.0
            }
        ]
        }');

        $agreement = new Agreement;
        $agreement->populate($json);

        $this->assertEquals($expected, $agreement->toArray());
    }

    public function providerMerchantDiscountRatesInvalid()
    {
        return [
        [true],
        [false],
        [''],
        [null],
        [
            [
            new MerchantDiscountRate,
            new MerchantDiscountRate(),
            Environment::sandbox('clientId', 'clientSecret'),
            new MerchantDiscountRate
            ]
        ]
        ];
    }
}
