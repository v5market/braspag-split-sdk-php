<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Sale\Credentials;

class CredentialsTest extends TestCase
{
    /**
     * @group ClassCredentials
     */
    public function testCreateNewInstance()
    {
        $instance = new Credentials;
        $this->assertInstanceOf(Credentials::class, $instance);
    }

    /**
     * @group ClassCredentials
     */
    public function testPopulateMethodShouldHaveNoErrors()
    {
        $json = '{"Code":"my-code","Key":"my-key","Username":"my-username","Password":"my-password","Signature":"my-signature"}';

        $credentials = new Credentials;
        $credentials->populate(json_decode($json));

        $this->assertEquals($json, json_encode($credentials));
    }
}
