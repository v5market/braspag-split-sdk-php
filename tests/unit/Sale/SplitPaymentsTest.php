<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Sale\SplitPayments;

class SplitPaymentsTest extends TestCase
{
    /**
     * @group SplitPayments
     */
    public function testCreateNewInstance()
    {
        $instance = new SplitPayments;
        $this->assertInstanceOf(SplitPayments::class, $instance);
    }

    /**
     * @group SplitPayments
     */
    public function testSetterSubordinateMerchantIdWithValidArgument()
    {
        $guid = '5a1a61f0-1630-4873-bf69-a6ff9ae664e9';
        $instance = new SplitPayments;
        $instance->setSubordinateMerchantId($guid);

        $this->assertEquals($guid, $instance->getSubordinateMerchantId());
    }

    /**
     * @group SplitPayments
     */
    public function testSetterSubordinateMerchantIdWithEmptyArgumentMustGiveError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new SplitPayments;
        $instance->setSubordinateMerchantId('');
    }

    /**
     * @group SplitPayments
     */
    public function testSetterAmountWithValidArgument()
    {
        $instance = new SplitPayments;
        $instance->setAmount(130793);

        $this->assertEquals(130793, $instance->getAmount());
    }

    /**
     * @group SplitPayments
     */
    public function testSetterFaresWithValidArgument()
    {
        $instance = new SplitPayments;
        $instance->setFares(1.0, 100);

        $this->assertEquals([
        "Mdr" => 1.0,
        "Fee" => 100
        ], $instance->getFares());
    }

    /**
     * @group SplitPayments
     */
    public function testAddOneSplitWithValidArgument()
    {
        $expected = [
        "95506357-f4c7-475f-a6b8-cf4618b9d721" => [
            "MerchantId" => "95506357-f4c7-475f-a6b8-cf4618b9d721",
            "Amount" => 500
        ]
        ];

        $instance = new SplitPayments;
        $instance->addSplit("95506357-f4c7-475f-a6b8-cf4618b9d721", 500);

        $this->assertEquals($expected, $instance->getSplits());
    }

    /**
     * @group SplitPayments
     */
    public function testSetterMultipleSplitWithValidArgument()
    {
        $expected = [
        "95506357-f4c7-475f-a6b8-cf4618b9d721" => [
            "MerchantId" => "95506357-f4c7-475f-a6b8-cf4618b9d721",
            "Amount" => 500
        ],
        "5a1a61f0-1630-4873-bf69-a6ff9ae664e9" => [
            "MerchantId" => "5a1a61f0-1630-4873-bf69-a6ff9ae664e9",
            "Amount" => 9500
        ]
        ];

        $instance = new SplitPayments;
        $instance->addSplit("95506357-f4c7-475f-a6b8-cf4618b9d721", 500);
        $instance->setSplits($expected);

        $this->assertEquals($expected, $instance->getSplits());
    }

    /**
     * @group SplitPayments
     */
    public function testSetterMultipleSplitWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);

        $expected = [
        "95506357-f4c7-475f-a6b8-cf4618b9d721" => [
            "Merchant" => "95506357-f4c7-475f-a6b8-cf4618b9d721",
            "Amount" => 500
        ],
        "5a1a61f0-1630-4873-bf69-a6ff9ae664e9" => [
            "MerchantId" => "5a1a61f0-1630-4873-bf69-a6ff9ae664e9",
            "Amount" => 9500
        ]
        ];

        $instance = new SplitPayments;
        $instance->addSplit("95506357-f4c7-475f-a6b8-cf4618b9d721", 500);
        $instance->setSplits($expected);
    }

    /**
     * @group SplitPayments
     */
    public function testWhetherToarrayReturnsDataAccordingToWhatTheApiRequests()
    {
        $json = '{"SubordinateMerchantId":"f2d6eb34-2c6b-4948-8fff-51facdd2a28f","Amount":10000,"Fares":{"Mdr":5,"Fee":0},"Splits":[{"MerchantId":"f2d6eb34-2c6b-4948-8fff-51facdd2a28f","Amount":9500},{"MerchantId":"f43fca07-48ec-46b5-8b93-ce79b75a8f63","Amount":500}]}';

        $instance = new SplitPayments;
        $instance->setSubordinateMerchantId('f2d6eb34-2c6b-4948-8fff-51facdd2a28f');
        $instance->setAmount(10000);
        $instance->setFares(5, 0);
        $instance->addSplit("f2d6eb34-2c6b-4948-8fff-51facdd2a28f", 9500);
        $instance->addSplit("f43fca07-48ec-46b5-8b93-ce79b75a8f63", 500);

        $this->assertEquals($json, json_encode($instance));
    }

    /**
     * @group SplitPayments
     */
    public function testPopulateMethodShouldHaveNoErrors()
    {
        $json = '{"SubordinateMerchantId":"f2d6eb34-2c6b-4948-8fff-51facdd2a28f","Amount":10000,"Fares":{"Mdr":5,"Fee":0},"Splits":[{"MerchantId":"f2d6eb34-2c6b-4948-8fff-51facdd2a28f","Amount":9500},{"MerchantId":"f43fca07-48ec-46b5-8b93-ce79b75a8f63","Amount":500}]}';

        $instance = new SplitPayments;
        $instance->populate(json_decode($json));

        $this->assertEquals($json, json_encode($instance));
    }
}
