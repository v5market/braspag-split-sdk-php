<?php

use Braspag\Split\Test\BraspagSplitTestCase;
use Braspag\Split\Domains\Schedule\Adjustment;

class AdjustmentTest extends BraspagSplitTestCase
{
    /**
     * @test
     * @group Adjustament
     * @group ClassAdjustment
     */
    public function createNewInstance()
    {
        $instance = new Adjustment;
        $this->assertInstanceOf(Adjustment::class, $instance);
    }

    /**
     * @test
     * @group Adjustament
     * @group ClassAdjustment
     */
    public function checksWhetherFillingInUsingMethodPopulateReturnsTheCorrectValues()
    {
        $expect = '{"id": "35402644-5654-46ae-9fb3-f8fbbfbf06e5","createdAt": "2020-06-26 19:45:45","createdBy": "' . $this->clientId . '","merchantIdToDebit": "' . $this->subordinateOne . '","merchantIdToCredit": "' . $this->subordinateTwo . '","forecastedDate": "2020-06-30","amount": 1000,"description": "Multa por não cumprimento do prazo de entrega no pedido XYZ","status": "Created"}';

        $instance = new Adjustment;
        $instance->populate(json_decode($expect));

        $this->assertJsonStringEqualsJsonString($expect, json_encode($instance));
    }

    /**
     * @test
     * @group Adjustament
     * @group ClassAdjustment
     */
    public function checksSetterAndMethodToArray()
    {
        $expect = '{"merchantIdToDebit": "EA4DB25A-F981-4849-87FF-026897E006C6","merchantIdToCredit": "44F68284-27CF-43CB-9D14-1B1EE3F36838","forecastedDate": "2018-09-17","amount": 1000,"description": "Multa por não cumprimento do prazo de entrega no pedido XYZ","transactionId": "717A0BD0-3D92-43DB-9D1E-9B82DFAFA392"}';

        $instance = new Adjustment;
        $instance->setMerchantIdToDebit('EA4DB25A-F981-4849-87FF-026897E006C6');
        $instance->setMerchantIdToCredit('44F68284-27CF-43CB-9D14-1B1EE3F36838');
        $instance->setForecastedDate('2018-09-17');
        $instance->setAmount(1000);
        $instance->setDescription('Multa por não cumprimento do prazo de entrega no pedido XYZ');
        $instance->setTransactionId('717A0BD0-3D92-43DB-9D1E-9B82DFAFA392');

        $this->assertJsonStringEqualsJsonString($expect, json_encode($instance));
    }
}
