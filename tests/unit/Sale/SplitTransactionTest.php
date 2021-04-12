<?php

use Braspag\Split\Test\BraspagSplitTestCase;
use Braspag\Split\Domains\Sale\SplitTransaction;

class SplitTransactionTest extends BraspagSplitTestCase
{
    /**
     * @test
     * @group SplitTransaction
     */
    public function createNewInstance()
    {
        $instance = new SplitTransaction;
        $this->assertInstanceOf(SplitTransaction::class, $instance);
    }

    /**
     * @test
     * @group SplitTransaction
     */
    public function populateDataWithoutError()
    {
        $json = '{"Id": "00000000-83b2-497f-af78-d62051b05125","Merchant": {"Id": "' . $this->clientId . '","TypeId": 3,"Type": "Master","FancyName": "opencart","CorporateName": "opencart"},"MasterRateDiscountTypeId": 2,"MasterRateDiscountType": "Sale","ReleasedToAnticipation": true,"Splits": [{"Id": "f2e0287c-89c1-4e04-8a7d-2f8529959dd2","NetAmount": 3975,"GrossAmount": 5000,"Fares": {"Mdr": 20,"Fee": 25,"DiscountedMdrAmount": 1000,"CustomPayoutFares": {}},"Merchant": {"Id": "' . $this->subordinateOne . '","TypeId": 4,"Type": "Subordinate","FancyName": "opencartSubordinado01","CorporateName": "opencartSubordinado01"},"PayoutBlocked": false},{"Id": "74a332fc-b9b0-4ab3-917d-da15ec3e63c6","NetAmount": 2235,"GrossAmount": 2500,"Fares": {"Mdr": 10,"Fee": 15,"DiscountedMdrAmount": 250,"CustomPayoutFares": {}},"Merchant": {"Id": "' . $this->subordinateTwo . '","TypeId": 4,"Type": "Subordinate","FancyName": "opencartSubordinado02","CorporateName": "opencartSubordinado02"},"PayoutBlocked": false},{"Id": "96ba5a37-a351-447a-9674-ae2ca3b295ef","NetAmount": 2200,"GrossAmount": 2500,"Fares": {"Mdr": 3,"Fee": 0,"DiscountedMdrAmount": 300,"CustomPayoutFares": {}},"Merchant": {"Id": "' . $this->clientId . '","TypeId": 3,"Type": "Master","FancyName": "opencart","CorporateName": "opencart"},"PayoutBlocked": false}],"MasterSummary": {"Commission": {"SplitId": "990b0e06-d6cf-4724-951b-33423f8841ac","NetAmount": 990,"GrossAmount": 990},"TotalGrossAmount": 3490,"TotalNetAmount": 3190},"TransactionFares": {"DiscountedAmount": 300,"AppliedMdr": 3,"Fee": 0}}';

        $instance = new SplitTransaction;
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));

        $this->assertEquals('00000000-83b2-497f-af78-d62051b05125', $instance->getId());
        $this->assertEquals($this->clientId, $instance->getMerchant()->getId());
        $this->assertEquals('opencart', $instance->getMerchant()->getFancyName());
        $this->assertCount(3, $instance->getSplits());
    }
}
