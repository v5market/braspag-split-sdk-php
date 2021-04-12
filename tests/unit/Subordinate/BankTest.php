<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Subordinate\Bank;
use \Braspag\Split\Domains\Subordinate\Document;
use \Braspag\Split\Constants\Subordinate\Bank as Constants;

class BankTest extends TestCase
{
    /**
     * @group ClassSubordinateBank
     */
    public function testCreateInstance()
    {
        $instance = new Bank;
        $this->assertInstanceOf(Bank::class, $instance);
    }

    /**
     * @group ClassSubordinateBank
     * @dataProvider providerIsValid
     */
    public function testIsValid($setter, $getter, $value, $expected)
    {
        $reflection = new \ReflectionClass(Bank::class);
        $instance = $reflection->newInstance();

        call_user_func([$instance, $setter], $value);

        $result = call_user_func([$instance, $getter]);

        $this->assertEquals($expected, $result);
    }

    /**
     * @group ClassSubordinateBank
     * @dataProvider providerIsInvalid
     */
    public function testIsInvalid($setter, $value, $exception)
    {
        $this->expectException($exception);
        $reflection = new \ReflectionClass(Bank::class);
        $instance = $reflection->newInstance();

        call_user_func([$instance, $setter], $value);
    }

    /**
     * @group ClassSubordinateBank
     */
    public function testConvertObjectToArray()
    {
        $expected = [
        "Bank" => "001",
        "BankAccountType" => "CheckingAccount",
        "Number" => "123456",
        "VerifierDigit" => "7",
        "AgencyNumber" => "1234",
        "AgencyDigit" => "x",
        "DocumentNumber" => "96462142000140",
        "DocumentType" => "Cnpj"
        ];

        $document = Document::cnpj("96462142000140");

        $bank = new Bank;
        $bank->setBank("001");
        $bank->setBankAccountType(Constants::ACCOUNT_TYPE_CHECKING);
        $bank->setNumber("123456");
        $bank->setVerifierDigit("7");
        $bank->setAgencyNumber("1234");
        $bank->setDocument($document);

        $this->assertEquals($expected, $bank->toArray());
    }

    /**
     * @group ClassSubordinateBank
     */
    public function testConvertJsonToObject()
    {
        $expected = [
        "Bank" => "001",
        "BankAccountType" => "CheckingAccount",
        "Number" => "0002",
        "Operation" => "2",
        "VerifierDigit" => "2",
        "AgencyNumber" => "0002",
        "AgencyDigit" => "2",
        "DocumentNumber" => "96462142000140",
        "DocumentType" => "Cnpj"
        ];

        $json = json_decode('{
        "Bank":"001",
        "BankAccountType":"CheckingAccount",
        "Number":"0002",
        "Operation":"2",
        "VerifierDigit":"2",
        "AgencyNumber":"0002",
        "AgencyDigit":"2",
        "DocumentNumber":"96462142000140",
        "DocumentType":"CNPJ"
        }');

        $bank = new Bank;
        $bank->populate($json);

        $this->assertEquals($expected, $bank->toArray());
    }

    public function providerIsValid()
    {
        return [
        ['setBank', 'getBank', '001', '001'],
        ['setBank', 'getBank', '757', '757'],

        ['setBankAccountType', 'getBankAccountType', 'CheckingAccount', 'CheckingAccount'],
        ['setBankAccountType', 'getBankAccountType', 'SavingsAccount', 'SavingsAccount'],
        ['setBankAccountType', 'getBankAccountType', Constants::ACCOUNT_TYPE_CHECKING, 'CheckingAccount'],
        ['setBankAccountType', 'getBankAccountType', Constants::ACCOUNT_TYPE_SAVINGS, 'SavingsAccount'],

        ['setNumber', 'getNumber', '123456', '123456'],

        ['setOperation', 'getOperation', '', ''],
        ['setOperation', 'getOperation', '013', '013'],

        ['setVerifierDigit', 'getVerifierDigit', '0', '0'],

        ['setAgencyNumber', 'getAgencyNumber', '1234', '1234'],

        ['setAgencyDigit', 'getAgencyDigit', '1', '1'],
        ['setAgencyDigit', 'getAgencyDigit', '', 'x'],

        ['setAgencyNumber', 'getAgencyFull', '1234', '1234-x'],
        ];
    }

    public function providerIsInvalid()
    {
        return [
        ['setBank', '', InvalidArgumentException::class],
        ['setBank', '1234', InvalidArgumentException::class],

        ['setBankAccountType', '', InvalidArgumentException::class],
        ['setBankAccountType', 'Poupança', InvalidArgumentException::class],

        ['setNumber', '', InvalidArgumentException::class],
        ['setNumber', str_repeat('V', 21), InvalidArgumentException::class],

        ['setOperation', str_repeat('V', 11), LengthException::class],

        ['setVerifierDigit', '10', InvalidArgumentException::class],

        ['setAgencyNumber', str_repeat('V', 16), LengthException::class],

        ['setAgencyDigit', '10', InvalidArgumentException::class],
        ];
    }
}
