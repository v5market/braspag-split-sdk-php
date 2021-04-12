<?php

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Schedule\Filter;

class FilterTest extends TestCase
{
    /**
     * @test
     * @group ClassFilter
     */
    public function createNewInstance()
    {
        $instance = new Filter;
        $this->assertInstanceOf(Filter::class, $instance);
    }

    /**
     * @test
     * @group ClassFilter
     */
    public function checksWhetherTheEventSearchFiltersAreCorrect()
    {
        $expected = 'initialForecastedDate=2017-12-01&finalForecastedDate=2018-12-31&merchantIds=e4db3e1b-985f-4e33-80cf-a19d559f0f60&merchantIds=7c7e5e7b-8a5d-41bf-ad91-b346e077f769&merchantIds=2b9f5bea-5504-40a0-8ae7-04c154b06b8b';

        $instance = new Filter;
        $instance->setInitialForecastedDate("2017-12-01");
        $instance->setFinalForecastedDate("2018-12-31");
        $instance->addMerchantId("e4db3e1b-985f-4e33-80cf-a19d559f0f60");
        $instance->addMerchantId("7c7e5e7b-8a5d-41bf-ad91-b346e077f769");
        $instance->addMerchantId("2b9f5bea-5504-40a0-8ae7-04c154b06b8b");

        $this->assertEquals($expected, (string)$instance);
    }

    /**
     * @test
     * @group ClassFilter
     */
    public function checksWhetherTheReturnOfDatesIsOfTypeDateTime()
    {
        $instance = new Filter;
        $instance->setInitialForecastedDate("2015-12-31");
        $instance->setFinalForecastedDate("2016-12-31");
        $instance->setInitialPaymentDate("2017-12-31");
        $instance->setFinalPaymentDate("2018-12-31");
        $instance->setInitialCaptureDate("2019-12-31");
        $instance->setFinalCaptureDate("2020-12-31");

        $this->assertContainsOnlyInstancesOf(DateTime::class, [
        $instance->getInitialForecastedDate(),
        $instance->getFinalForecastedDate(),
        $instance->getInitialPaymentDate(),
        $instance->getFinalPaymentDate(),
        $instance->getInitialCaptureDate(),
        $instance->getFinalCaptureDate(),
        ]);
    }
}
