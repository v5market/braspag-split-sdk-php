<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Address;

class AddressTest extends TestCase
{
    /**
     * @group ClassAddress
     */
    public function testCreateInstance()
    {
        $instance = new Address;
        $this->assertInstanceOf(Address::class, $instance);
    }

    /**
     * @group ClassAddress
     * @dataProvider providerIsValid
     */
    public function testValid($setter, $getter, $value = '', $expected = null)
    {
        $reflection = new ReflectionClass(Address::class);
        $instance = $reflection->newInstance();
        call_user_func([$instance, $setter], $value);

        $result = call_user_func([$instance, $getter]);

        $this->assertEquals($expected === null ? $value : $expected, $result);
    }

    /**
     * @group ClassAddress
     */
    public function testCheckCountryReturnNull()
    {
        $instance = new Address();
        $this->assertNull($instance->getCountry());
    }

    /**
     * @group ClassAddress
     * @dataProvider providerIsInvalid
     */
    public function testInvalid($method, $value, $exception)
    {
        $this->expectException($exception);
        $reflection = new ReflectionClass(Address::class);
        $instance = $reflection->newInstance();
        call_user_func([$instance, $method], $value);
    }

    /**
     * @group ClassAddress
     */
    public function testsWhetherTheToArrayMethodIsReturningCorrectly() {
        $street = 'Avenida Brasil';
        $number = '44878';
        $neighborhood = 'Campo Grande';
        $city = 'Rio de Janeiro';
        $state = 'RJ';
        $zipcode = '23078001';

        $address = new Address;
        $address->setStreet($street);
        $address->setNumber($number);
        $address->setNeighborhood($neighborhood);
        $address->setCity($city);
        $address->setState($state);
        $address->setZipcode($zipcode);

        $this->assertEquals([
        'Street' => $street,
        'Number' => $number,
        'Neighborhood' => $neighborhood,
        'City' => $city,
        'State' => $state,
        'ZipCode' => $zipcode,
        ], $address->toArray());
    }

    /**
     * @group ClassAddress
     */
    public function testsWhetherTheFromJsonMethodIsReturningCorrectly() {
        $expected = [
        'Street' => 'Avenida Brasil',
        'Number' => '44878',
        'Neighborhood' => 'Campo Grande',
        'City' => 'Rio de Janeiro',
        'State' => 'RJ',
        'ZipCode' => '23078001',
        'Country' => 'BR',
        'District' => 'Alphaville'
        ];

        $json = json_decode('{
        "Street": "Avenida Brasil",
        "Number": "44878",
        "Neighborhood": "Campo Grande",
        "City": "Rio de Janeiro",
        "State": "RJ",
        "ZipCode": "23078001",
        "Country": "BR",
        "District": "Alphaville"
        }');

        $address = new Address;
        $address->populate($json);

        $this->assertEquals($expected, $address->toArray());
    }

    /**
     * @group ClassAddress
     */
    public function testJsonSerialize() {
        $expected = '{"Street":"Avenida Brasil","Number":"44878","Complement":"MAR","Neighborhood":"Campo Grande","City":"Rio de Janeiro","State":"RJ","ZipCode":"23078001"}';

        $address = new Address;
        $address->populate(json_decode($expected));

        $this->assertEquals($expected, json_encode($address));
    }

    public function providerIsValid()
    {
        return [
        ['setStreet', 'getStreet', 'Avenida Brasil'],
        ['setNumber', 'getNumber', '44878'],
        ['setComplement', 'getComplement'],
        ['setComplement', 'getComplement', 'MAR'],
        ['setNeighborhood', 'getNeighborhood', 'Campo Grande'],
        ['setCity', 'getCity', 'Rio de Janeiro'],
        ['setState', 'getState', 'RJ'],
        ['setZipcode', 'getZipcode', '23078001', '23078001'],
        ['setZipcode', 'getZipcode', '23078-001', '23078001'],
        ['setZipcode', 'getZipcode', '23.078-001', '23078001'],
        ['setZipcode', 'getZipcode', '2.3.0.7.8-0-0-1', '23078001'],
        ];
    }

    public function providerIsInvalid()
    {
        return [
        ['setStreet', '', InvalidArgumentException::class],
        ['setNumber', '', InvalidArgumentException::class],
        ['setNeighborhood', '', InvalidArgumentException::class],
        ['setCity', '', InvalidArgumentException::class],
        ['setState', '', LengthException::class],
        ['setZipcode', '', LengthException::class],
        ['setZipcode', '2307800l', LengthException::class],
        ['setZipcode', '230780o1', LengthException::class],
        ['setZipcode', '207800001', LengthException::class],
        ];
    }
}
