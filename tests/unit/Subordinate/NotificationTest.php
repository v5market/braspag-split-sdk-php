<?php

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Subordinate\Notification;

class NotificationTest extends TestCase
{
    /**
     * @group ClassSubordinateNotification
     */
    public function testCreateInstance()
    {
        $instance = new Notification;
        $this->assertInstanceOf(Notification::class, $instance);
    }

    /**
     * @group ClassSubordinateNotification
     */
    public function testConvertToArray()
    {
        $expected = [
        "Url" => "https://example.com/callback",
        "Headers" => [
            [
            "Key" => "X-Id",
            "Value" => "0123456789"
            ]
        ]
        ];

        $instance = new Notification();
        $instance->setUrl("https://example.com/callback");
        $instance->addHeader("X-Id", "0123456789");

        $this->assertEquals($expected, $instance->toArray());
    }

    /**
     * @group ClassSubordinateNotification
     */
    public function testConvertToArrayWithoutHeaders()
    {
        $expected = [
        "Url" => "https://example.com/callback"
        ];

        $instance = new Notification();
        $instance->setUrl("https://example.com/callback");

        $this->assertEquals($expected, $instance->toArray());
    }

    /**
     * @group ClassSubordinateNotification
     */
    public function testConvertToObject()
    {
        $expected = [
        "Url" => "https://site.com.br/api/subordinados",
        "Headers" => [
            [
            "Key" => "key1",
            "Value" => "value1"
            ],
            [
            "Key" => "key2",
            "Value" => "value2"
            ]
        ]
        ];

        $instance = new Notification();
        $instance->populate(json_decode('{
        "Url": "https://site.com.br/api/subordinados",
        "Headers": [{
            "Key": "key1",
            "Value": "value1"
        },
        {
            "Key": "key2",
            "Value": "value2"
        }]
        }'));

        $this->assertEquals($expected, $instance->toArray());
    }
}
