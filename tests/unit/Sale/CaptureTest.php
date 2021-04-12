<?php

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Sale\Capture;
use Braspag\Split\Domains\Sale\SplitPayments;

class CaptureTest extends TestCase
{
    /**
     * @group Capture
     * @group ClassCapture
     */
    public function testCreateNewInstance()
    {
        $instance = new Capture;
        $this->assertInstanceOf(Capture::class, $instance);
    }

    /**
     * @group Capture
     * @group ClassCapture
     * @dataProvider providerSettersAndGetters
     */
    public function testSettersAndGetters($method, $value)
    {
        $reflection = new ReflectionClass(Capture::class);
        $instance = $reflection->newInstance();
        $setter = call_user_func([$instance, "set$method"], $value);
        $getter = call_user_func([$instance, "get$method"]);

        $this->assertEquals($value, $getter);
        $this->assertInstanceOf(Capture::class, $setter);
    }

    /**
     * @group Capture
     * @group ClassCapture
     */
    public function testSetterSplitsWithInvalidArgument()
    {
        $this->expectException(TypeError::class);
        $instance = new Capture;
        $instance->addSplitPayments(null);
    }

    /**
     * @group Capture
     * @group ClassCapture
     */
    public function testSetterMultipleSplitsWithInvalidArgument()
    {
        $this->expectException(TypeError::class);
        $instance = new Capture;
        $instance->setSplitPayments([
        new SplitPayments,
        null
        ]);
    }

    /**
     * @group Capture
     * @group ClassCapture
     */
    public function testSetterSplitsWithValidArgument()
    {
        $instance = new Capture;
        $instance->addSplitPayments(new SplitPayments);
        $instance->addSplitPayments(new SplitPayments);

        $this->assertCount(2, $instance->getSplitPayments());
    }

    /**
     * @group Capture
     * @group ClassCapture
     */
    public function testSetterMultipleSplitsWithValidArgument()
    {
        $one = new SplitPayments;
        $one->setSubordinateMerchantId('f2d6eb34-2c6b-4948-8fff-51facdd2a28f');
        $one->setAmount(10000);
        $one->setFares(5, 0);
        $one->addSplit("f2d6eb34-2c6b-4948-8fff-51facdd2a28f", 1000);
        $one->addSplit("f43fca07-48ec-46b5-8b93-ce79b75a8f63", 500);

        $two = new SplitPayments;
        $two->setSubordinateMerchantId('123456-2c6b-4948-8fff-51facdd2a28f');
        $two->setAmount(10000);
        $two->setFares(5, 0);
        $two->addSplit("123456-2c6b-4948-8fff-51facdd2a28f", 9500);
        $two->addSplit("f43fca07-48ec-46b5-8b93-ce79b75a8f63", 1000);

        $instance = new Capture;
        $instance->setSplitPayments([$one, $two]);

        $splitPayments = $instance->getSplitPayments();

        $this->assertSame($one, $splitPayments[0]);
        $this->assertSame($two, $splitPayments[1]);
    }

    /**
     * @group Capture
     * @group ClassCapture
     */
    public function testReturnMethodToArrayNoShouldGiveError()
    {
        $json = '{"Status":2,"ReasonMessage":"Successful","ProviderReturnCode":"6","ProviderReturnMessage":"Operation Successful","ReturnCode":"6","ReasonCode":null,"ReturnMessage":"Operation Successful","SplitPayments":[{"SubordinateMerchantId":"e5147542-0c0e-45d4-b6a8-a5a7167e6ae7","Amount":6000,"Fares":{"Mdr":5,"Fee":30},"Splits":[{"MerchantId":"e5147542-0c0e-45d4-b6a8-a5a7167e6ae7","Amount":5670},{"MerchantId":"4b3f216c-69d7-44cf-a2d1-dbd1439429c3","Amount":330}]},{"SubordinateMerchantId":"f1531485-adb3-4320-9b14-dbc07eea2b3e","Amount":4000,"Fares":{"Mdr":4,"Fee":15},"Splits":[{"MerchantId":"f1531485-adb3-4320-9b14-dbc07eea2b3e","Amount":3825},{"MerchantId":"4b3f216c-69d7-44cf-a2d1-dbd1439429c3","Amount":175}]}]}';

        $instance = new Capture;
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    public function providerSettersAndGetters()
    {
        return [
        ['Status', 2],
        ['ReasonCode', 0],
        ['ReasonMessage', 'Successful'],
        ['ProviderReturnCode', '6'],
        ['ProviderReturnMessage', 'Operation Successful'],
        ['ReturnCode', '6'],
        ['ReturnMessage', 'Operation Successful']
        ];
    }
}
