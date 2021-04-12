<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Sale\ExternalAuthentication;

class ExternalAuthenticationTest extends TestCase
{
    /**
     * @group ClassExternalAuthentication
     */
    public function testCreateNewInstance()
    {
        $instance = new ExternalAuthentication;
        $this->assertInstanceOf(ExternalAuthentication::class, $instance);
    }

    /**
     * @group ClassExternalAuthentication
     */
    public function testMethodPopulateWithValidArgument()
    {
        $expect = '
        {
        "Cavv": "AAABB2gHA1B5EFNjWQcDAAAAAAB=",
        "Xid": "Uk5ZanBHcWw2RjRCbEN5dGtiMTB=",
        "Eci": "5"
        }
        ';

        $instance = new ExternalAuthentication;
        $instance->populate(json_decode($expect));

        $this->assertJsonStringEqualsJsonString($expect, json_encode($instance));
    }
}
