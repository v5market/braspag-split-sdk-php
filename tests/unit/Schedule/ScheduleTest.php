<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Schedule\Schedule;

class ScheduleTest extends TestCase
{
    /**
     * @test
     * @group ClassSchedule
     */
    public function createNewInstance()
    {
        $instance = new Schedule;
        $this->assertInstanceOf(Schedule::class, $instance);
    }

    /**
     * @test
     * @group ClassSchedule
     */
    public function checksWhetherFillingInUsingMethodPopulateReturnsTheCorrectValues()
    {
        $json = '{"Id":"b579fafb-8271-4a1d-a657-00e5fd9b9f83","PaymentId":"069ee5ef-ce7a-43ce-a9af-022f652e115a","MerchantId":"ea4db25a-f981-4849-87ff-026897e006c6","ForecastedDate":"2018-08-22","Installments":10,"InstallmentAmount":9255,"InstallmentNumber":6,"Event":1,"EventDescription":"Credit","EventStatus":"Settled","SourceId":"e3efe82f-1eee-4c28-bb9f-8054fcd4ca3f","Mdr":3.2,"Commission":false}';

        $instance = new Schedule;
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
