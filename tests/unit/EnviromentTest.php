<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Environment;

final class EnvironmentTest extends TestCase
{
    public function testCheckSandbox()
    {
        $url = "https://splitsandbox.braspag.com.br/";
        $obj = Environment::sandbox('clientId', 'clientSecret');

        $this->assertEquals($url, $obj->getUrl());
    }

    public function testCheckProduction()
    {
        $url = "https://split.braspag.com.br/";
        $obj = Environment::production('clientId', 'clientSecret');

        $this->assertEquals($url, $obj->getUrl());
    }

    public function testGetterClientId()
    {
        $obj = Environment::sandbox('clientId', 'clientSecret');

        $this->assertEquals('clientId', $obj->getClientId());
    }

    public function testGetterClientSecret()
    {
        $obj = Environment::sandbox('clientId', 'clientSecret');

        $this->assertEquals('clientSecret', $obj->getClientSecret());
    }

    public function testAuthorization()
    {
        $expected = 'Y2xpZW50SWQ6Y2xpZW50U2VjcmV0';
        $obj = Environment::sandbox('clientId', 'clientSecret');

        $this->assertEquals($expected, $obj->getAuthorization());
    }
}
