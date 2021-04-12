<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Cart\Cart;
use \Braspag\Split\Domains\Cart\Item as CartItem;

class CartTest extends TestCase
{
    /**
     * @group ClassCart
     */
    public function testCreateInstance()
    {
        $instance = new Cart;
        $this->assertInstanceOf(Cart::class, $instance);
    }

    /**
     * @group ClassCart
     */
    public function testSetterIsGift()
    {
        $instance = new Cart;
        $instance->setIsGift(true);

        $this->assertTrue($instance->getIsGift() === $instance->isGift());
    }

    /**
     * @group ClassCart
     */
    public function testAddItemWithValidArgument()
    {
        $cartItem = new CartItem;
        $instance = new Cart;
        $instance->addItem($cartItem);

        $this->assertContains($cartItem, $instance->getItems());
    }

    /**
     * @group ClassCart
     */
    public function testSetMultipleItemsWithValidArgument()
    {
        $cartItems = [
        new CartItem,
        new CartItem
        ];

        $instance = new Cart;
        $instance->setItems($cartItems);

        $this->assertEquals($cartItems, $instance->getItems());
    }

    /**
     * @group ClassCart
     */
    public function testSetterMultipleItemsWithValidArgument()
    {
        $cartItems = [
        new CartItem,
        new CartItem
        ];

        $cartItem = new CartItem;

        $instance = new Cart;
        $instance->addItem($cartItem);
        $instance->setItems($cartItems);

        $this->assertNotContains($cartItems, $instance->getItems());
    }

    /**
     * @group ClassCart
     */
    public function testWhetherToarrayReturnsDataAccordingToWhatTheApiRequests()
    {
        $expected = [
        "IsGift" => true,
        "Items" => [
            [
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
            ]
        ]
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

        $instance = new Cart;
        $instance->setIsGift(true);
        $instance->addItem($item);

        $this->assertEquals($expected, $instance->toArray());
    }

    /**
     * @group ClassCart
     */
    public function testPopulateMethodShouldHaveNoErrors()
    {
        $json = '{
        "IsGift": false,
        "ReturnsAccepted": true,
        "Items": [
            {
            "GiftCategory": "off",
            "HostHedge": "off",
            "NonSensicalHedge": "off",
            "ObscenitiesHedge": "off",
            "PhoneHedge": "off",
            "Name": "ItemTeste1",
            "Quantity": 1,
            "Sku": "20170511",
            "UnitPrice": 10000,
            "Risk": "high",
            "TimeHedge": "normal",
            "Type": "adultcontent",
            "VelocityHedge": "high"
            },
            {
            "GiftCategory": "off",
            "HostHedge": "off",
            "NonSensicalHedge": "off",
            "ObscenitiesHedge": "off",
            "PhoneHedge": "off",
            "Name": "ItemTeste2",
            "Quantity": 1,
            "Sku": "20170512",
            "UnitPrice": 10000,
            "Risk": "high",
            "TimeHedge": "normal",
            "Type": "adultcontent",
            "VelocityHedge": "high"
            }
        ]
        }';

        $item = new Cart;
        $item->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($item));
    }
}
