<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Cart\Item as CartItem;
use \Braspag\Split\Constants\Cart\Item as Constant;

class ItemTest extends TestCase
{
    public function testCreateInstance()
    {
        $instance = new CartItem;
        $this->assertInstanceOf(CartItem::class, $instance);
    }

    /**
     * @dataProvider providerGiftCategory
     */
    public function testSetterGiftCategoryWithValidArgument($arg, $expect)
    {
        $instance = new CartItem;
        $instance->setGiftCategory($arg);

        $this->assertEquals($expect, $instance->getGiftCategory());
    }

    public function testSetterGiftCategoryWithInvalidArgumentShouldHaveErrors()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CartItem;
        $instance->setGiftCategory(Constant::VALUE_HIGH);
    }

    /**
     * @dataProvider providerImportanceLevel
     */
    public function testSetterHostHedgeWithValidArgument($arg, $expect)
    {
        $instance = new CartItem;
        $instance->setHostHedge($arg);

        $this->assertEquals($expect, $instance->getHostHedge());
    }

    public function testSetterHostHedgeWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CartItem;
        $instance->setHostHedge(Constant::VALUE_YES);
    }

    /**
     * @dataProvider providerImportanceLevel
     */
    public function testNonSensicalHedgeWithValidArgument($arg, $expect)
    {
        $instance = new CartItem;
        $instance->setNonSensicalHedge($arg);

        $this->assertEquals($expect, $instance->getNonSensicalHedge());
    }

    public function testNonSensicalHedgeWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CartItem;
        $instance->setNonSensicalHedge(Constant::VALUE_NO);
    }

    /**
     * @dataProvider providerImportanceLevel
     */
    public function testObscenitiesHedgeWithValidArgument($arg, $expect)
    {
        $instance = new CartItem;
        $instance->setObscenitiesHedge($arg);

        $this->assertEquals($expect, $instance->getObscenitiesHedge());
    }

    public function testObscenitiesHedgeWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CartItem;
        $instance->setObscenitiesHedge(Constant::VALUE_NO);
    }

    /**
     * @dataProvider providerImportanceLevel
     */
    public function testPhoneHedgeWithValidArgument($arg, $expect)
    {
        $instance = new CartItem;
        $instance->setPhoneHedge($arg);

        $this->assertEquals($expect, $instance->getPhoneHedge());
    }

    public function testPhoneHedgeWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CartItem;
        $instance->setPhoneHedge(Constant::VALUE_NO);
    }

    /**
     * @dataProvider providerImportanceLevel
     */
    public function testTimeHedgeWithValidArgument($arg, $expect)
    {
        $instance = new CartItem;
        $instance->setTimeHedge($arg);

        $this->assertEquals($expect, $instance->getTimeHedge());
    }

    public function testTimeHedgeWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CartItem;
        $instance->setTimeHedge(Constant::VALUE_NO);
    }

    /**
     * @dataProvider providerCartItemTypes
     */
    public function testCartItemsWithValidArgument($arg, $expect)
    {
        $instance = new CartItem;
        $instance->setType($arg);

        $this->assertEquals($expect, $instance->getType());
    }

    public function testWhetherTheValueOfTheDefaultTypeIsDefault()
    {
        $instance = new CartItem;
        $this->assertEquals('Default', $instance->getType());
    }

    public function testCartItemWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CartItem;
        $instance->setType('Invalid');
    }

    public function testWhetherToarrayReturnsDataAccordingToWhatTheApiRequests()
    {
        $expect = [
        "GiftCategory" => "off",
        "HostHedge" => "off",
        "NonSensicalHedge" => "off",
        "ObscenitiesHedge" => "off",
        "PhoneHedge" => "off",
        "Name" => "ItemTeste1",
        "Quantity" => 1,
        "Sku" => "20170511",
        "UnitPrice" => 10000,
        "Risk" => "high",
        "TimeHedge" => "normal",
        "Type" => "adultcontent",
        "VelocityHedge" => "high"
        ];

        $item = new CartItem;
        $item->setGiftCategory('off');
        $item->setHostHedge('off');
        $item->setNonSensicalHedge('off');
        $item->setObscenitiesHedge('off');
        $item->setPhoneHedge('off');
        $item->setName('ItemTeste1');
        $item->setQuantity(1);
        $item->setSku('20170511');
        $item->setUnitPrice(10000);
        $item->setRisk('high');
        $item->setTimeHedge('normal');
        $item->setType('adultContent');
        $item->setVelocityHedge('high');

        $this->assertEquals($expect, $item->toArray());
    }

    public function testPopulateMethodShouldHaveNoErrors()
    {
        $obj = '{"GiftCategory":"off","HostHedge":"off","NonSensicalHedge":"off","ObscenitiesHedge":"off","PhoneHedge":"off","Name":"ItemTeste1","Quantity":1,"Sku":"20170511","UnitPrice":10000,"Risk":"high","TimeHedge":"normal","Type":"adultcontent","VelocityHedge":"high"}';

        $item = new CartItem;
        $item->populate(json_decode($obj));

        $this->assertEquals($obj, json_encode($item));
    }

    public function providerGiftCategory()
    {
        return [
        [Constant::VALUE_YES, Constant::VALUE_YES],
        ['Yes', Constant::VALUE_YES],
        ['NO', Constant::VALUE_NO],
        ['no', Constant::VALUE_NO]
        ];
    }

    public function providerImportanceLevel()
    {
        return [
        [Constant::VALUE_HIGH, Constant::VALUE_HIGH],
        ['High', Constant::VALUE_HIGH],
        ['low', Constant::VALUE_LOW],
        ['NormAL', Constant::VALUE_NORMAL],
        ['off', Constant::VALUE_OFF],
        ];
    }

    public function providerCartItemTypes()
    {
        return [
        ['AdultContent', 'adultcontent'],
        ['Coupon', 'coupon'],
        ['EletronicGood', 'eletronicgood'],
        ];
    }
}
