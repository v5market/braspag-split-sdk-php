<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Subordinate\Merchant;

class MerchantTest extends TestCase
{
    /**
     * @group ClassSubordinateMerchant
     */
    public function testCreateInstance()
    {
        $instance = new Merchant;
        $this->assertInstanceOf(Merchant::class, $instance);
    }

    /**
     * @dataProvider providerValidArguments
     * @group ClassSubordinateMerchant
     */
    public function testLengthOfValuesWithValidArguments($setter, $getter, $arg)
    {
        $reflection = new \ReflectionClass(Merchant::class);
        $instance = $reflection->newInstance();
        call_user_func([$instance, $setter], $arg);

        $result = call_user_func([$instance, $getter]);

        $this->assertEquals($arg, $result);
    }

    /**
     * @dataProvider providerInvalidArguments
     * @group ClassSubordinateMerchant
     */
    public function testLengthOfValuesWithInvalidArguments($setter, $arg)
    {
        $this->expectException(LengthException::class);
        $reflection = new \ReflectionClass(Merchant::class);
        $instance = $reflection->newInstance();

        call_user_func([$instance, $setter], $arg);
    }

    /**
     * @group ClassSubordinateMerchant
     */
    public function testConvertToArray()
    {
        $expected = [
        'MasterMerchantId' => '665a33c5-0022-4a40-a0bd-daad04eb3236',
        'MerchantId' => 'b8ccc729-a874-4b51-a5a9-ffeb5bd98878',
        'CorporateName' => 'Subordinado Corporativo Ltda',
        'FancyName' => 'Subordinado Nome Fantasia',
        'DocumentNumber' => '96462142000140',
        'DocumentType' => 'Cnpj',
        'MerchantCategoryCode' => '5719',
        'ContactName' => 'Nome do Contato do Subordinado',
        'ContactPhone' => '11987654321',
        'MailAddress' => 'addres@email.mail.com',
        'Website' => 'https://www.website.com.br',
        'Blocked' => true,
        'Analysis' =>
        [
            'Status' => 'UnderAnalysis',
        ],
        'BankAccount' =>
        [
            'Bank' => '001',
            'BankAccountType' => 'CheckingAccount',
            'Number' => '0002',
            'Operation' => '2',
            'VerifierDigit' => '2',
            'AgencyNumber' => '0002',
            'AgencyDigit' => '2',
            'DocumentNumber' => '96462142000140',
            'DocumentType' => 'Cnpj',
        ],
        'Address' =>
        [
            'Street' => 'Rua Teste',
            'Number' => '50',
            'Complement' => 'AP 255',
            'Neighborhood' => 'Centro',
            'City' => 'São Paulo',
            'State' => 'SP',
            'ZipCode' => '12345687',
        ],
        'Agreement' =>
        [
            'Fee' => 10,
            'MerchantDiscountRates' =>
            [
            [
                'PaymentArrangement' =>
                [
                'Product' => 'DebitCard',
                'Brand' => 'Master',
                ],
                'InitialInstallmentNumber' => 1,
                'FinalInstallmentNumber' => 1,
                'Percent' => 4,
            ],
            [
                'PaymentArrangement' =>
                [
                'Product' => 'CreditCard',
                'Brand' => 'Master',
                ],
                'InitialInstallmentNumber' => 1,
                'FinalInstallmentNumber' => 1,
                'Percent' => 4,
            ],
            [
                'PaymentArrangement' =>
                [
                'Product' => 'CreditCard',
                'Brand' => 'Master',
                ],
                'InitialInstallmentNumber' => 2,
                'FinalInstallmentNumber' => 6,
                'Percent' => 4,
            ],
            [
                'PaymentArrangement' =>
                [
                'Product' => 'CreditCard',
                'Brand' => 'Master',
                ],
                'InitialInstallmentNumber' => 7,
                'FinalInstallmentNumber' => 12,
                'Percent' => 4,
            ],
            ],
        ],
        'Notification' =>
        [
            'Url' => 'https://site.com.br/api/subordinados',
            'Headers' =>
            [
            [ 'Key' => 'key1', 'Value' => 'value1' ],
            [ 'Key' => 'key2', 'Value' => 'value2' ],
            ],
        ],
        'Attachments' =>
        [
            [
            'AttachmentType' => 'ProofOfBankDomicile',
            'File' => [ 'Name' => 'comprovante', 'FileType' => 'png' ],
            ],
        ],
        ];

        $json = json_decode('{
        "MasterMerchantId": "665a33c5-0022-4a40-a0bd-daad04eb3236",
        "MerchantId": "b8ccc729-a874-4b51-a5a9-ffeb5bd98878",
        "CorporateName":"Subordinado Corporativo Ltda",
        "FancyName":"Subordinado Nome Fantasia",
        "DocumentNumber":"96462142000140",
        "DocumentType":"CNPJ",
        "MerchantCategoryCode":"5719",
        "ContactName":"Nome do Contato do Subordinado",
        "ContactPhone":"11987654321",
        "MailAddress":"addres@email.mail.com",
        "Website":"https://www.website.com.br",
        "Blocked": true,
        "Analysis": {
            "Status": "UnderAnalysis"
        },
        "BankAccount": {
            "Bank":"001",
            "BankAccountType":"CheckingAccount",
            "Number":"0002",
            "Operation":"2",
            "VerifierDigit":"2",
            "AgencyNumber":"0002",
            "AgencyDigit":"2",
            "DocumentNumber":"96462142000140",
            "DocumentType":"CNPJ"
        },
        "Address":{
            "Street":"Rua Teste",
            "Number":"50",
            "Complement":"AP 255",
            "Neighborhood":"Centro",
            "City":"São Paulo",
            "State" : "SP",
            "ZipCode": "12345687"
        },
        "Agreement":{
            "Fee" : 10,
            "MerchantDiscountRates": [{
                "MerchantDiscountRateId": "662e340f-07f2-4827-816d-b1878eb03eae",
                "PaymentArrangement": {
                    "Product": "DebitCard",
                    "Brand": "Master"
                },
                "InitialInstallmentNumber" : 1,
                "FinalInstallmentNumber" : 1,
                "Percent" : 4
            },
            {
                "MerchantDiscountRateId": "eb9d6357-7ad1-4fe0-90fe-364cff7ff0fd",
                "PaymentArrangement": {
                    "Product": "CreditCard",
                    "Brand": "Master"
                },
                "InitialInstallmentNumber" : 1,
                "FinalInstallmentNumber" : 1,
                "Percent" : 4
            },
            {
                "MerchantDiscountRateId": "d09fe9d3-98c7-4c37-9bd3-7c1c91ee15de",
                "PaymentArrangement": {
                    "Product": "CreditCard",
                    "Brand": "Master"
                },
                "InitialInstallmentNumber" : 2,
                "FinalInstallmentNumber" : 6,
                "Percent" : 4
            },
            {
                "MerchantDiscountRateId": "e2515c24-fd73-4b8e-92ad-cfe2b95239de",
                "PaymentArrangement": {
                    "Product": "CreditCard",
                    "Brand": "Master"
                },
                "InitialInstallmentNumber" : 7,
                "FinalInstallmentNumber" : 12,
                "Percent" : 4
            }]
        },
        "Notification": {
            "Url": "https://site.com.br/api/subordinados",
            "Headers": [{
                "Key": "key1",
                "Value": "value1"
            },
            {
                "Key": "key2",
                "Value": "value2"
            }]
        },
        "Attachments": [{
            "AttachmentType": "ProofOfBankDomicile",
            "File": {
                "Name": "comprovante",
                "FileType": "png"
            }
        }]
        }');

        $instance = new Merchant;
        $instance->populate($json);

        $this->assertEquals($expected, $instance->toArray());
    }

    public function providerValidArguments()
    {
        return [
        ['setMasterMerchantId', 'getMasterMerchantId', '665a33c5'],
        ['setMerchantId', 'getMerchantId', 'b8ccc729-a874-4b51-a5a9-ffeb5bd98878'],
        ['setCorporateName', 'getCorporateName', 'Subordinado Corporativo Ltda'],
        ['setFancyName', 'getFancyName', 'Subordinado Nome Fantasia'],
        ['setMerchantCategoryCode', 'getMerchantCategoryCode', '5719'],
        ['setContactName', 'getContactName', 'Nome do Contato do Subordinado'],
        ['setContactPhone', 'getContactPhone', '11913071993'],
        ['setMailAddress', 'getMailAddress', 'addres@email.mail.com'],
        ['setWebsite', 'getWebsite', 'https://www.website.com.br'],
        ['setWebsite', 'getWebsite', 'https://cam.example.com/onvif1'],
        ['setBlocked', 'getBlocked', true],
        ['setBlocked', 'getBlocked', 1],
        ['setBlocked', 'getBlocked', false],
        ['setBlocked', 'getBlocked', null],
        ['setBlocked', 'getBlocked', 0],
        ['setBlocked', 'getBlocked', ''],
        ];
    }

    public function providerInvalidArguments()
    {
        return [
        ['setMasterMerchantId', str_repeat('V', 38)],
        ['setMerchantId', str_repeat('V', 38)],
        ['setCorporateName', str_repeat('V', 101)],
        ['setFancyName', str_repeat('V', 51)],
        ['setContactName', str_repeat('V', 101)]
        ];
    }
}
