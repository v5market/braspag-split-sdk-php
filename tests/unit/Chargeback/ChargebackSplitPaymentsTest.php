<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Chargeback\ChargebackSplitPayments;

class ChargebackSplitPaymentsTest extends TestCase
{
    /**
     * @test
     * @group ClassChangebackSplitPayments
     */
    public function createNewInstance()
    {
        $instance = new ChargebackSplitPayments;
        $this->assertInstanceOf(ChargebackSplitPayments::class, $instance);
    }

    /**
     * @test
     * @group ClassChangebackSplitPayments
     */
    public function checksWhetherFillingInUsingMethodPopulateReturnsTheCorrectValues()
    {
        $json = '{"SubordinateMerchantId": "00000000-0000-0000-0000-000000000000","ChargebackAmount": 4000,"ChargebackSplits": [{"MerchantId": "00000000-0000-0000-0000-000000000000","ChargebackAmount": 378},{"MerchantId": "11111111-1111-1111-1111-111111111111","ChargebackAmount": 220}]}';

        $instance = new ChargebackSplitPayments;
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
