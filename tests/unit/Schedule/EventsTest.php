<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Schedule\Events;

class EventsTest extends TestCase
{
    /**
     * @test
     * @group ClassEvents
     */
    public function createNewInstance()
    {
        $instance = new Events;
        $this->assertInstanceOf(Events::class, $instance);
    }

    /**
     * @test
     * @group ClassEvents
     */
    public function checksWhetherFillingInUsingMethodPopulateReturnsTheCorrectValues()
    {
        $json = '{"PageCount": 1,"PageSize": 25,"PageIndex": 1,"Schedules": [{"Id": "b579fafb-8271-4a1d-a657-00e5fd9b9f83","PaymentId": "069ee5ef-ce7a-43ce-a9af-022f652e115a","MerchantId": "ea4db25a-f981-4849-87ff-026897e006c6","ForecastedDate": "2018-08-22","Installments": 10,"InstallmentAmount": 9255,"InstallmentNumber": 6,"Event": 1,"EventDescription": "Credit","EventStatus": "Settled","SourceId": "e3efe82f-1eee-4c28-bb9f-8054fcd4ca3f","Mdr": 3.2,"Commission": false},{"Id": "2f110f0d-82c9-4a1f-8df5-08203348d160","PaymentId": "069ee5ef-ce7a-43ce-a9af-022f652e115a","MerchantId": "ea4db25a-f981-4849-87ff-026897e006c6","ForecastedDate": "2018-08-22","Installments": 10,"InstallmentAmount": 9255,"InstallmentNumber": 9,"Event": 1,"EventDescription": "Credit","EventStatus": "Settled","SourceId": "e3efe82f-1eee-4c28-bb9f-8054fcd4ca3f","Mdr": 3.2,"Commission": false},{"Id": "01d9b78f-b287-4376-a5e4-12d91cde1938","PaymentId": "069ee5ef-ce7a-43ce-a9af-022f652e115a","MerchantId": "ea4db25a-f981-4849-87ff-026897e006c6","ForecastedDate": "2018-08-22","Installments": 10,"InstallmentAmount": 9255,"InstallmentNumber": 2,"Event": 1,"EventDescription": "Credit","EventStatus": "Settled","SourceId": "e3efe82f-1eee-4c28-bb9f-8054fcd4ca3f","Mdr": 3.2,"Commission": false},{"Id": "e30760d7-01e2-4b2b-9a43-2b252fcfbd84","PaymentId": "069ee5ef-ce7a-43ce-a9af-022f652e115a","MerchantId": "ea4db25a-f981-4849-87ff-026897e006c6","ForecastedDate": "2018-08-22","Installments": 10,"InstallmentAmount": 9262,"InstallmentNumber": 10,"Event": 1,"EventDescription": "Credit","EventStatus": "Settled","SourceId": "e3efe82f-1eee-4c28-bb9f-8054fcd4ca3f","Mdr": 3.2,"Commission": false},{"Id": "90ea1e11-568f-49ee-bc3f-7ab2a225a1e1","PaymentId": "069ee5ef-ce7a-43ce-a9af-022f652e115a","MerchantId": "ea4db25a-f981-4849-87ff-026897e006c6","ForecastedDate": "2018-08-22","Installments": 10,"InstallmentAmount": 9255,"InstallmentNumber": 1,"Event": 1,"EventDescription": "Credit","EventStatus": "Settled","SourceId": "e3efe82f-1eee-4c28-bb9f-8054fcd4ca3f","Mdr": 3.2,"Commission": false}]}';

        $instance = new Events;
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
        $this->assertEquals(25, $instance->getPageSize());
    }
}
