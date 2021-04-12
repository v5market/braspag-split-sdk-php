<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\FraudAnalysis\Browser;

class BrowserTest extends TestCase
{
    /**
     * @group ClassBrowser
     */
    public function testCreateInstance()
    {
        $instance = new Browser;
        $this->assertInstanceOf(Browser::class, $instance);
    }

    /**
     * @group ClassBrowser
     */
    public function testEmailWithValidArgument()
    {
        $instance = new Browser;
        $instance->setEmail('example@example.com');

        $this->assertEquals('example@example.com', $instance->getEmail());
    }

    /**
     * @group ClassBrowser
     */
    public function testEmailWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Browser;
        $instance->setEmail('invalid*@email.');
    }

    /**
     * @group ClassBrowser
     */
    public function testSetterIpAddresWithValidArgument()
    {
        $instance = new Browser;
        $instance->setIpAddress('2001:db8:a0b:12f0::1');

        $this->assertEquals('2001:db8:a0b:12f0::1', $instance->getIpAddress());
    }

    /**
     * @group ClassBrowser
     */
    public function testSetterIpAddresWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Browser;
        $instance->setIpAddress('45.65.85.256');
    }

    /**
     * @group ClassBrowser
     */
    public function testWhetherToarrayReturnsDataAccordingToWhatTheApiRequests()
    {
        $expect = [
        "CookiesAccepted" => false,
        "Email" => "comprador@braspag.com.br",
        "HostName" => "Teste",
        "IpAddress" => "127.0.0.1",
        "Type" => "Chrome",
        ];

        $browser = new Browser;
        $browser->setCookiesAccepted(false);
        $browser->setEmail('comprador@braspag.com.br');
        $browser->setHostName('Teste');
        $browser->setIpAddress('127.0.0.1');
        $browser->setType('Chrome');

        $this->assertEquals($expect, $browser->toArray());
    }

    /**
     * @group ClassBrowser
     */
    public function testPopulateMethodShouldHaveNoErrors()
    {
        $json = '{"HostName":"Teste","CookiesAccepted":false,"Email":"comprador@braspag.com.br","Type":"Chrome","IpAddress":"127.0.0.1"}';

        $browser = new Browser;
        $browser->populate(json_decode($json));

        $this->assertEquals($json, json_encode($browser));
    }
}
