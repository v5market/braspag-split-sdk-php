<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Sale\Customer;
use \Braspag\Split\Domains\Sale\Identity;
use \Braspag\Split\Domains\Address;

class CustomerTest extends TestCase
{
    /**
     * @group ClassCustomer
     */
    public function testCreateInstance()
    {
        $instance = new Customer;
        $this->assertInstanceOf(Customer::class, $instance);
    }

    /**
     * @group ClassCustomer
     */
    public function testCustomerNameWithValidArguments()
    {
        $name = 'Joe';

        $instance = new Customer;
        $instance->setName($name);

        $this->assertEquals($name, $instance->getName());
    }

    /**
     * @group ClassCustomer
     */
    public function testCustomerNameWithInvalidArguments()
    {
        $this->expectException(TypeError::class);
        $instance = new Customer;
        $instance->setName(null);
    }

    /**
     * @group ClassCustomer
     */
    public function testCustomerEmailWithValidArguments()
    {
        $email = 'example@example.com';

        $instance = new Customer;
        $instance->setName('Joe');
        $instance->setEmail($email);

        $this->assertEquals($email, $instance->getEmail());
    }

    /**
     * @group ClassCustomer
     */
    public function testEmailWithValidArguments()
    {
        $email = 'test@test.com';
        $instance = new Customer;
        $instance->setEmail($email);

        $this->assertEquals($email, $instance->getEmail());
    }

    /**
     * @group ClassCustomer
     */
    public function testPhoneWithValidArgument()
    {
        $instance = new Customer;
        $instance->setPhone('71 1234-5678');

        $this->assertEquals('7112345678', $instance->getPhone());
    }

    /**
     * @group ClassCustomer
     * @dataProvider providerCellPhoneValid
     */
    public function testCellCellPhoneWithValidArgument($arg)
    {
        $instance = new Customer;
        $instance->setMobile($arg);

        $expected = preg_replace('/\D/', '', $arg);

        $this->assertEquals($expected, $instance->getMobile());
    }

    /**
     * @group ClassCustomer
     * @dataProvider providerCellPhoneInvalid
     */
    public function testCellPhoneWithInvalidArgument($arg)
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Customer;
        $instance->setMobile($arg);
    }

    /**
     * @group ClassCustomer
     */
    public function testDeliveryAddressWithValidArgument()
    {
        $address = new Address;

        $instance = new Customer;
        $instance->setDeliveryAddress($address);

        $this->assertEquals($instance->getDeliveryAddress()->toArray(), $address->toArray());
    }

    /**
     * @group ClassCustomer
     */
    public function testBillingAddressWithValidArgument()
    {
        $address = new Address;
        $address->setStreet('Rua');

        $instance = new Customer;
        $instance->setAddress($address);

        $this->assertEquals($instance->getAddress()->toArray(), $address->toArray());
    }

    /**
     * @group ClassCustomer
     */
    public function testWhetherToarrayReturnsDataAccordingToWhatTheApiRequests()
    {
        $expected = [
        "Name" => "Nome do Comprador",
        "Identity" => "46133873027",
        "IdentityType" => "Cpf",
        "Email" => "comprador@braspag.com.br",
        "Birthdate" => "1991-01-02",
        "Phone" =>  "5521976781114",
        "Address" => [
            "Street" => "Alameda Xingu",
            "Number" => "512",
            "Complement" => "27 andar",
            "ZipCode" => "12345987",
            "City" => "São Paulo",
            "State" => "SP",
            "Country" => "BR",
            "District" => "Alphaville"
        ],
        "DeliveryAddress" => [
            "Street" => "Alameda Xingu",
            "Number" => "512",
            "Complement" => "27 andar",
            "ZipCode" => "12345987",
            "City" => "São Paulo",
            "State" => "SP",
            "Country" => "BR",
            "District" => "Alphaville"
        ]
        ];

        $address = new Address;
        $address->setStreet('Alameda Xingu');
        $address->setNumber('512');
        $address->setComplement('27 andar');
        $address->setZipCode('12345987');
        $address->setCity('São Paulo');
        $address->setState('SP');
        $address->setCountry('BR');
        $address->setDistrict('Alphaville');

        $identity = Identity::cpf('461.338.730-27');

        $customer = new Customer;
        $customer->setName('Nome do Comprador');
        $customer->setIdentity($identity);
        $customer->setEmail('comprador@braspag.com.br');
        $customer->setBirthdate(DateTime::createFromFormat('Y-m-d', '1991-01-02'));
        $customer->setPhone('5521976781114');
        $customer->setAddress($address);
        $customer->setDeliveryAddress($address);

        $this->assertEquals($expected, $customer->toArray());
    }

    /**
     * @group ClassCustomer
     */
    public function testPopulateMethodShouldHaveNoErrors()
    {
        $json = '{"Name":"NomedoComprador","Identity":"46133873027","IdentityType":"Cpf","Email":"comprador@braspag.com.br","Birthdate":"1991-01-02","Phone":"5521976781114","Address":{"Street":"AlamedaXingu","Number":"512","Complement":"27 andar","City":"S\u00e3oo Paulo","State":"SP","ZipCode":"12345987","Country":"BR","District":"Alphaville"},"DeliveryAddress":{"Street":"AlamedaXingu","Number":"512","Complement":"27 andar","City":"S\u00e3oo Paulo","State":"SP","ZipCode":"12345987","Country":"BR","District":"Alphaville"}}';

        $customer = new Customer;
        $customer->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($customer));
    }

    public function providerCellPhoneValid()
    {
        return [
        ['+55 71 9 1234-5678'],
        ['55 71 9 1234-5678'],
        ['+5571 9 1234-5678'],
        ['+55719 1234-5678'],
        ['+557191234-5678'],
        ['557191234-5678'],
        ['55 71 9 12345678'],
        ];
    }

    public function providerCellPhoneInvalid()
    {
        return [
        ['+55 71 1234-5678'],
        ['55 7 9 1234-5678'],
        ['571 9 1234-5678'],
        ['71 1234-5678']
        ];
    }
}
