<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Schedule\Transactions;

class TransactionsTest extends TestCase
{
    /**
     * @test
     * @group ClassTransactions
     */
    public function createNewInstance()
    {
        $instance = new Transactions;
        $this->assertInstanceOf(Transactions::class, $instance);
    }

    /**
     * @test
     * @group ClassTransactions
     */
    public function checksWhetherFillingInUsingMethodPopulateReturnsTheCorrectValues()
    {
        $json = '{"PageCount":1,"PageSize":25,"PageIndex":1,"Transactions":[{"PaymentId":"24afaaaf-f2a1-40a5-bb25-f914fa623c4c","CapturedDate":"2017-12-01","Schedules":[{"MerchantId":"2b9f5bea-5504-40a0-8ae7-04c154b06b8b","ForecastedDate":"2017-12-21","Installments":2,"InstallmentAmount":24357,"InstallmentNumber":1,"Event":"Credit","EventDescription":"Credit","EventStatus":"Scheduled"},{"MerchantId":"e4db3e1b-985f-4e33-80cf-a19d559f0f60","ForecastedDate":"2017-12-21","Installments":2,"InstallmentAmount":1450,"InstallmentNumber":1,"Event":"Credit","EventDescription":"Credit","EventStatus":"Scheduled"},{"MerchantId":"7c7e5e7b-8a5d-41bf-ad91-b346e077f769","ForecastedDate":"2017-12-21","Installments":2,"InstallmentAmount":38480,"InstallmentNumber":1,"Event":"Credit","EventDescription":"Credit","EventStatus":"Scheduled"},{"MerchantId":"e4db3e1b-985f-4e33-80cf-a19d559f0f60","ForecastedDate":"2017-12-21","Installments":2,"InstallmentAmount":5,"InstallmentNumber":1,"Event":"FeeDebit","EventDescription":"FeeDebit","EventStatus":"Scheduled"}]}]}';

        $instance = new Transactions;
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
        $this->assertEquals(25, $instance->getPageSize());
    }
}
