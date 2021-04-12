<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Sale\Cancellation;
use Braspag\Split\Domains\Sale\VoidSplitPayments;

class CancellationTest extends TestCase
{
    /**
     * @group ClassCancellation
     */
    public function testCreateNewInstance()
    {
        $instance = new Cancellation;
        $this->assertInstanceOf(Cancellation::class, $instance);
    }

    /**
     * @group ClassCancellation
     */
    public function testMethodPopulateWithValidArgument()
    {
        $expect = '{"Status": 10,"ReasonCode": 0,"ReasonMessage": "Successful","ProviderReturnCode": "0","ProviderReturnMessage": "Operation Successful","ReturnCode": "0","ReturnMessage": "Operation Successful","VoidSplitPayments": [{"SubordinateMerchantId": "e5147542-0c0e-45d4-b6a8-a5a7167e6ae7","VoidedAmount": 4000,"VoidedSplits": [{"MerchantId": "e5147542-0c0e-45d4-b6a8-a5a7167e6ae7","VoidedAmount": 3825},{"MerchantId": "4b3f216c-69d7-44cf-a2d1-dbd1439429c3","VoidedAmount": 175}]},{"SubordinateMerchantId": "f1531485-adb3-4320-9b14-dbc07eea2b3e","VoidedAmount": 6000,"VoidedSplits": [{"MerchantId": "f1531485-adb3-4320-9b14-dbc07eea2b3e","VoidedAmount": 5670},{"MerchantId": "4b3f216c-69d7-44cf-a2d1-dbd1439429c3","VoidedAmount": 330}]}]}';

        $json = '{"Status": 10,"ReasonCode": 0,"ReasonMessage": "Successful","ProviderReturnCode": "0","ProviderReturnMessage": "Operation Successful","ReturnCode": "0","ReturnMessage": "Operation Successful","Links": [{"Method": "GET","Rel": "self","Href": "https://apiquerysandbox.cieloecommerce.cielo.com.br/1/sales/019efd18-c69a-4107-b5d7-e86564460cc4"}],"VoidSplitPayments": [{"SubordinateMerchantId": "e5147542-0c0e-45d4-b6a8-a5a7167e6ae7","VoidedAmount": 4000,"VoidedSplits": [{"MerchantId": "e5147542-0c0e-45d4-b6a8-a5a7167e6ae7","VoidedAmount": 3825},{"MerchantId": "4b3f216c-69d7-44cf-a2d1-dbd1439429c3","VoidedAmount": 175}]},{"SubordinateMerchantId": "f1531485-adb3-4320-9b14-dbc07eea2b3e","VoidedAmount": 6000,"VoidedSplits": [{"MerchantId": "f1531485-adb3-4320-9b14-dbc07eea2b3e","VoidedAmount": 5670},{"MerchantId": "4b3f216c-69d7-44cf-a2d1-dbd1439429c3","VoidedAmount": 330}]}]}';

        $instance = new Cancellation;
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($expect, json_encode($instance));
    }
}
