<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Sale\Payment;
use \Braspag\Split\Domains\Sale\SplitPayments;
use \Braspag\Split\Domains\Sale\ExternalAuthentication;
use \Braspag\Split\Domains\PaymentMethod\CreditCard;
use \Braspag\Split\Domains\PaymentMethod\DebitCard;
use \Braspag\Split\Domains\PaymentMethod\Boleto;
use \Braspag\Split\Domains\FraudAnalysis\FraudAnalysis;
use \Braspag\Split\Domains\FraudAnalysis\Browser;
use \Braspag\Split\Domains\FraudAnalysis\ReplyData;
use \Braspag\Split\Domains\Cart\Cart;
use \Braspag\Split\Domains\Cart\Item as CartItem;
use \Braspag\Split\Constants\Sale\Currency;

class PaymentTest extends TestCase
{
    /**
     * @group ClassPayment
     */
    public function testCreateInstance()
    {
        $instance = new Payment;
        $this->assertInstanceOf(Payment::class, $instance);
    }

    /**
     * @group ClassPayment
     */
    public function testTypeWithValidArgument()
    {
        $instanceOne = new Payment;
        $instanceOne->setType('CreditCard');

        $instanceTwo = new Payment;
        $instanceTwo->setType('DebitCard');

        $this->assertEqualsIgnoringCase([
        $instanceOne->getType(),
        $instanceTwo->getType(),
        ], [
        'CreditCard',
        'DebitCard'
        ]);
    }

    /**
     * @group ClassPayment
     */
    public function testAmountWithValidArgument()
    {
        $instance = new Payment;
        $instance->setAmount(13071993);

        $this->assertEquals(13071993, $instance->getAmount());
    }

    /**
     * @group ClassPayment
     */
    public function testServiceTaxAmountWithValidArgument()
    {
        $instance = new Payment;
        $instance->setServiceTaxAmount(13071993);

        $this->assertEquals(13071993, $instance->getServiceTaxAmount());
    }

    /**
     * @group ClassPayment
     * @dataProvider providerCurrencyValid
     */
    public function testCurrencyWithValidArgument($arg, $expected)
    {
        $instance = new Payment;
        $instance->setCurrency($arg);

        $this->assertEquals($expected, $instance->getCurrency());
    }

    /**
     * @group ClassPayment
     */
    public function testCurrencyWithInvalidArgument()
    {
        $this->expectException(OutOfBoundsException::class);
        $instance = new Payment;
        $instance->setCurrency('Invalid');
    }

    /**
     * @group ClassPayment
     */
    public function testInstallmentsWithValidArgument()
    {
        $instance = new Payment;
        $instance->setInstallments(13071993);

        $this->assertEquals(13071993, $instance->getInstallments());
    }

    /**
     * @group ClassPayment
     */
    public function testInterestWithArgumentValid()
    {
        $instanceOne = new Payment;
        $instanceOne->setInterest('ByMerchant');

        $instanceTwo = new Payment;
        $instanceTwo->setInterest('ByIssuer');

        $this->assertEquals([
        $instanceOne->getInterest(),
        $instanceTwo->getInterest()
        ], [
        'ByMerchant',
        'ByIssuer'
        ]);
    }

    /**
     * @group ClassPayment
     */
    public function testSoftDescriptorWithValidArgument()
    {
        $instance = new Payment;
        $instance->setSoftDescriptor('Empresa LTDA');

        $this->assertEquals('Empresa LTDA', $instance->getSoftDescriptor());
    }

    /**
     * @group ClassPayment
     * @dataProvider providerDoSplit
     */
    public function testWhetherClassSetterDoSplitConvertsToBoolean($arg, $expected)
    {
        $instance = new Payment;
        $instance->setDoSplit($arg);

        $this->assertEquals($expected, $instance->getDoSplit());
    }

    /**
     * @group ClassPayment
     */
    public function testSetterExtraDataCollection()
    {
        $name = 'CreatedAt';
        $value = '742571100';

        $instance = new Payment;
        $instance->addExtraDataCollection($name, $value);

        $getter = $instance->getExtraDataCollection();

        $this->assertEquals($value, $getter[0]['Value']);
    }

    /**
     * @group ClassPayment
     */
    public function testSetterExtraDataCollectionWithNameEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $name = '';
        $value = '742571100';

        $instance = new Payment;
        $instance->addExtraDataCollection($name, $value);
    }

    /**
     * @group ClassPayment
     */
    public function testSetterCreditCardWithValidArgument()
    {
        $card = new CreditCard;
        $card->setCardNumber('4111111111111111');
        $card->setHolder('Joe Joe');
        $card->setExpirationDate('12/2020');
        $card->setSecurityCode('123');
        $card->setBrand('Visa');

        $instance = new Payment;
        $instance->setPaymentMethod($card);

        $this->assertEquals('4111111111111111', $instance->getPaymentMethod()->getCardNumber());
    }

    /**
     * @group ClassPayment
     */
    public function testSetterDebitCardWithValidArgument()
    {
        $card = new DebitCard;
        $card->setCardNumber('4111111111111111');
        $card->setHolder('Joe Joe');
        $card->setExpirationDate('12/2020');
        $card->setSecurityCode('123');
        $card->setBrand('Visa');

        $instance = new Payment;
        $instance->setPaymentMethod($card);

        $this->assertEquals('4111111111111111', $instance->getPaymentMethod()->getCardNumber());
    }

    /**
     * @group ClassPayment
     */
    public function testSetterSplitPaymentsWithValidArgument()
    {
        $splits = [
        "95506357-f4c7-475f-a6b8-cf4618b9d721" => [
            "MerchantId" => "95506357-f4c7-475f-a6b8-cf4618b9d721",
            "Amount" => 500
        ],
        "5a1a61f0-1630-4873-bf69-a6ff9ae664e9" => [
            "MerchantId" => "5a1a61f0-1630-4873-bf69-a6ff9ae664e9",
            "Amount" => 9500
        ]
        ];

        $splitPayments = new SplitPayments;
        $splitPayments->setSplits($splits);

        $instance = new Payment;
        $instance->addSplitPayments($splitPayments);

        $this->assertEquals([$splitPayments], $instance->getSplitPayments());
        $this->assertContains([
            "MerchantId" => "95506357-f4c7-475f-a6b8-cf4618b9d721",
            "Amount" => 500
        ], $instance->getSplitPayments()[0]->getSplits());
    }

    /**
     * @group ClassPayment
     */
    public function testSetterAuthenticateConvertValueToBoolean()
    {
        $instance = new Payment;
        $instance->setAuthenticate('123');

        $this->assertTrue($instance->getAuthenticate());
    }

    /**
     * @group ClassPayment
     */
    public function testSetterrecurrentConvertValueToBoolean()
    {
        $instance = new Payment;
        $instance->setRecurrent(null);

        $this->assertFalse($instance->getRecurrent());
    }

    /**
     * @group ClassPayment
     */
    public function testSetterAuthenticationUrlWithValidArgument()
    {
        $instance = new Payment;
        $instance->setAuthenticationUrl('https://www.example.com/auth');

        $this->assertEquals('https://www.example.com/auth', $instance->getAuthenticationUrl());
    }

    /**
     * @group ClassPayment
     */
    public function testSetterAuthenticationUrlWithInvalidUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment;
        $instance->setAuthenticationUrl('https://www.ex*mple.');
    }

    /**
     * @group ClassPayment
     */
    public function testSetterReturnUrlWithValidArgument()
    {
        $instance = new Payment;
        $instance->setReturnUrl('https://www.example.com/return');

        $this->assertEquals('https://www.example.com/return', $instance->getReturnUrl());
    }

    /**
     * @group ClassPayment
     */
    public function testSetterReturnUrlWithInvalidUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment;
        $instance->setReturnUrl('https://www.ex*mple.');
    }

    /**
     * @group ClassPayment
     */
    public function testWhetherToArrayReturnsDataAccordingToWhatTheApiRequests()
    {
        $browser = new Browser;
        $browser->setCookiesAccepted(false);
        $browser->setEmail('comprador@braspag.com.br');
        $browser->setHostName('Teste');
        $browser->setIpAddress('127.0.0.1');
        $browser->setType('Chrome');

        $cartItem = new CartItem;
        $cartItem->setGiftCategory('off');
        $cartItem->setHostHedge('off');
        $cartItem->setNonSensicalHedge('off');
        $cartItem->setObscenitiesHedge('off');
        $cartItem->setPhoneHedge('off');
        $cartItem->setName('ItemTeste1');
        $cartItem->setQuantity(1);
        $cartItem->setSku('20170511');
        $cartItem->setUnitPrice(10000);
        $cartItem->setRisk('high');
        $cartItem->setTimeHedge('normal');
        $cartItem->setType('adultContent');
        $cartItem->setVelocityHedge('high');

        $cart = new Cart;
        $cart->setReturnsAccepted(true);
        $cart->addItem($cartItem);

        $replyDataJson = '{"AddressInfoCode":"COR-BA^MM-BIN","FactorCode":"B^D^R^Z","Score":42,"BinCountry":"us","CardIssuer":"FIA CARD SERVICES, N.A.","CardScheme":"VisaCredit","HostSeverity":1,"InternetInfoCode":"FREE-EM^RISK-EM","IpRoutingMethod":"Undefined","ScoreModelUsed":"default_lac","CasePriority":3,"ProviderTransactionId":"5220688414326697303008"}';

        $replyData = new ReplyData;
        $replyData->populate(json_decode($replyDataJson));

        $fraudAnalysis = new FraudAnalysis;
        $fraudAnalysis->setId('0e4d0a3c-e424-4fa5-a573-4eabbd44da42');
        $fraudAnalysis->setSequence('AnalyseFirst');
        $fraudAnalysis->setProvider('Cybersource');
        $fraudAnalysis->setSequenceCriteria('OnSuccess');
        $fraudAnalysis->setCaptureOnLowRisk(true);
        $fraudAnalysis->setTotalOrderAmount(10000);
        $fraudAnalysis->setFingerPrintId('id123456');
        $fraudAnalysis->setBrowser($browser);
        $fraudAnalysis->setCart($cart);
        $fraudAnalysis->addMerchantDefinedFields(2, '100');
        $fraudAnalysis->addMerchantDefinedFields(4, 'Web');
        $fraudAnalysis->addMerchantDefinedFields(9, 'SIM');
        $fraudAnalysis->setShippingAddressee('João das Couves');
        $fraudAnalysis->setShippingMethod('LowCost');
        $fraudAnalysis->setShippingPhone('551121840540');
        $fraudAnalysis->setStatus(1);
        $fraudAnalysis->setFraudAnalysisReasonCode(100);
        $fraudAnalysis->setReplyData($replyData);

        $card = new CreditCard;
        $card->setCardNumber('4111111111111111');
        $card->setHolder('Nome do Portador');
        $card->setExpirationDate('12/2021');
        $card->setSecurityCode('123');
        $card->setBrand('Visa');
        $card->setSaveCard(false);

        $instance = new Payment;
        $instance->setProvider('Simulado');
        $instance->setType('CreditCard');
        $instance->setAmount(10000);
        $instance->setInstallments(1);
        $instance->setPaymentMethod($card);
        $instance->setFraudAnalysis($fraudAnalysis);
        $instance->setCurrency(Currency::BRL);
        $instance->setCountry('BRA');
        $instance->setInterest('ByMerchant');
        $instance->setCapture(true);
        $instance->setAuthenticate(false);
        $instance->setRecurrent(false);
        $instance->setSoftDescriptor('Mensagem');
        $instance->setDoSplit(true);
        $instance->addExtraDataCollection('NomeDoCampo', 'ValorDoCampo');

        $this->assertEquals($this->expectedWhetherToArrayReturnsDataAccordingToWhatTheApiRequests(), $instance->toArray());
    }

    /**
     * @group ClassPayment
     */
    public function testPopulateMethodShouldHaveNoErrors()
    {
        $json = '{"Provider":"Simulado","Type":"creditcard","Amount":10000,"Currency":"BRL","Country":"BRA","Installments":1,"Interest":"ByMerchant","Capture":true,"SoftDescriptor":"Mensagem","DoSplit":true,"ExtraDataCollection":[{"Name":"NomeDoCampo","Value":"ValorDoCampo"}],"CreditCard":{"CardNumber":"455187******0181","Holder":"Nome do Portador","ExpirationDate":"12\/2021","SecurityCode":"123","Brand":"Visa","SaveCard":false},"FraudAnalysis":{"Id":"0e4d0a3c-e424-4fa5-a573-4eabbd44da42","Status":1,"Sequence":"AnalyseFirst","SequenceCriteria":"OnSuccess","Provider":"Cybersource","TotalOrderAmount":10000,"FingerPrintId":"074c1ee676ed4998ab66491013c565e2","Browser":{"HostName":"Teste","CookiesAccepted":false,"Email":"comprador@braspag.com.br","Type":"Chrome","IpAddress":"127.0.0.1"},"Cart":{"ReturnsAccepted":true,"Items":[{"GiftCategory":"undefined","HostHedge":"off","NonSensicalHedge":"off","ObscenitiesHedge":"off","PhoneHedge":"off","Name":"ItemTeste1","Quantity":1,"Sku":"20170511","UnitPrice":10000,"Risk":"high","TimeHedge":"normal","Type":"adultcontent","VelocityHedge":"high"},{"GiftCategory":"undefined","HostHedge":"off","NonSensicalHedge":"off","ObscenitiesHedge":"off","PhoneHedge":"off","Name":"ItemTeste2","Quantity":1,"Sku":"20170512","UnitPrice":10000,"Risk":"high","TimeHedge":"normal","Type":"adultcontent","VelocityHedge":"high"}]},"MerchantDefinedFields":[{"Id":2,"Value":"100"},{"Id":4,"Value":"Web"},{"Id":9,"Value":"SIM"}],"Shipping":{"Addressee":"Jo\u00e3o das Couves","Method":"LowCost","Phone":"551121840540"},"FraudAnalysisReasonCode":100,"ReplyData":{"AddressInfoCode":"COR-BA^MM-BIN","FactorCode":"B^D^R^Z","Score":42,"BinCountry":"us","CardIssuer":"FIA CARD SERVICES, N.A.","CardScheme":"VisaCredit","HostSeverity":1,"InternetInfoCode":"FREE-EM^RISK-EM","IpRoutingMethod":"Undefined","ScoreModelUsed":"default_lac","CasePriority":3,"ProviderTransactionId":"5220688414326697303008"}},"PaymentId":"c374099e-c474-4916-9f5c-f2598fec2925","ProofOfSale":"20170510053219433","AcquirerTransactionId":"0510053219433","AuthorizationCode":"936403","ReceivedDate":"2017-05-10 17:32:19","CapturedDate":"2017-05-10 17:32:19","CapturedAmount":10000,"ReasonMessage":"Successful","Status":2,"ProviderReturnCode":"6","ProviderReturnMessage":"Operation Successful"}';

        $instance = new Payment;
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @group ClassPayment
     */
    public function testSetBoletoAsAFormPaymentMethodWithoutErrors()
    {
        $boleto = new Boleto();

        $instance = new Payment;
        $instance->setPaymentMethod($boleto);

        $this->assertEquals($boleto, $instance->getPaymentMethod());
    }

    /**
     * @group ClassPayment
     */
    public function testSerializeToJsonWithBoletoDataWithoutErrors()
    {
        $json = '
        {
            "BoletoNumber":"2017091101",
            "Assignor": "Empresa Teste",
            "Demonstrative": "Desmonstrative Teste",
            "ExpirationDate": "2030-12-31",
            "Identification": "00000000000191",
            "Instructions": "Aceitar somente até a data de vencimento.",
            "DaysToFine": 1,
            "FineRate": 10.00000,
            "FineAmount": 1000,
            "DaysToInterest": 1,
            "InterestRate": 5.00000,
            "InterestAmount": 500
        }
        ';

        $boleto = new Boleto();
        $boleto->setBoletoNumber('2017091101');
        $boleto->setAssignor('Empresa Teste');
        $boleto->setDemonstrative('Desmonstrative Teste');
        $boleto->setExpirationDate(DateTime::createFromFormat('Y-m-d', '2030-12-31'));
        $boleto->setIdentification('00.000.000/0001-91');
        $boleto->setInstructions('Aceitar somente até a data de vencimento.');
        $boleto->setDaysToFine(1);
        $boleto->setFineRate(10.00000);
        $boleto->setFineAmount(1000);
        $boleto->setDaysToInterest(1);
        $boleto->setInterestRate(5.00000);
        $boleto->setInterestAmount(500);

        $instance = new Payment();
        $instance->setPaymentMethod($boleto);

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @group ClassPayment
     */
    public function testPopulateWithBoletoDataWithoutErrors()
    {
        $json = '
        {
            "Instructions": "Aceitar somente até a data de vencimento.",
            "ExpirationDate": "2017-12-31",
            "Demonstrative": "Desmonstrative Teste",
            "Url": "https://transactionsandbox.pagador.com.br/post/pagador/reenvia.asp/d605c399-96b2-4bb9-ae75-33824ec01be9",
            "BoletoNumber": "0000000155",
            "BarCodeNumber": "",
            "DigitableLine": "",
            "Assignor": "Empresa Teste",
            "Address": "ESTRADA TENENTE MARQUES, 1818, SALA 6 B",
            "Identification": "12346578909",
            "IsRecurring": false,
            "InterestAmount": 500,
            "InterestRate": 5.0,
            "FineRate": 10.0,
            "FineAmount": 1000,
            "DaysToFine": 1,
            "DaysToInterest": 1,
            "Bank": "BancoDoBrasil",
            "PaymentId": "d605c399-96b2-4bb9-ae75-33824ec01be9",
            "Type": "Boleto",
            "Amount": 10000,
            "ReceivedDate": "2019-12-03 12:05:37",
            "Currency": "BRL",
            "Country": "BRA",
            "Provider": "Braspag",
            "ReasonCode": 0,
            "ReasonMessage": "Successful",
            "Status": 1,
            "ProviderReturnCode": "0",
            "ProviderReturnMessage": "Transação criada com sucesso",
            "Links": [
                {
                    "Method": "GET",
                    "Rel": "self",
                    "Href": "https://apiquerysandbox.braspag.com.br/v2/sales/d605c399-96b2-4bb9-ae75-33824ec01be9"
                }
            ]
        }
        ';

        $jsonExpectedBoleto = '
        {
            "Address": "ESTRADA TENENTE MARQUES, 1818, SALA 6 B",
            "Instructions": "Aceitar somente até a data de vencimento.",
            "ExpirationDate": "2017-12-31",
            "Demonstrative": "Desmonstrative Teste",
            "Url": "https://transactionsandbox.pagador.com.br/post/pagador/reenvia.asp/d605c399-96b2-4bb9-ae75-33824ec01be9",
            "BoletoNumber": "0000000155",
            "BarCodeNumber": "",
            "DigitableLine": "",
            "Assignor": "Empresa Teste",
            "Identification": "12346578909",
            "InterestAmount": 500,
            "InterestRate": 5.0,
            "FineRate": 10.0,
            "FineAmount": 1000,
            "DaysToFine": 1,
            "DaysToInterest": 1,
            "Bank": "BancoDoBrasil",
            "Status": 1
        }
        ';

        $instance = new Payment();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($jsonExpectedBoleto, json_encode($instance->getPaymentMethod()));
    }

    public function providerCurrencyValid()
    {
        return [
        ['BRL', 'BRL'],
        ['usd', 'USD'],
        ['MxN', 'MXN'],
        ['COp', 'COP'],
        ['cLP', 'CLP'],
        ['ARS', 'ARS'],
        ['PEN', 'PEN'],
        ['EUR', 'EUR'],
        ['PYN', 'PYN'],
        ['UYU', 'UYU'],
        ['VEB', 'VEB'],
        ['VEF', 'VEF'],
        ['GBP', 'GBP'],
        ];
    }

    /**
     * @group ClassPayment
     */
    public function testePaymentWithExternalAuthentication()
    {
        $json = '
        {
            "ExternalAuthentication": {
                "Cavv": "AAABB2gHA1B5EFNjWQcDAAAAAAB=",
                "Xid": "Uk5ZanBHcWw2RjRCbEN5dGtiMTB=",
                "Eci": "5"
            }
        }
        ';

        $ea = new ExternalAuthentication();
        $ea->setCavv('AAABB2gHA1B5EFNjWQcDAAAAAAB=');
        $ea->setXid('Uk5ZanBHcWw2RjRCbEN5dGtiMTB=');
        $ea->setEci('5');

        $instance = new Payment();
        $instance->setExternalAuthentication($ea);

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    public function providerDoSplit()
    {
        return [
        [true, true],
        [1, true],
        ['123', true],
        [1.0, true],
        ['ABC', true],
        ['Yes', true],
        ['Sim', true],
        [[123], true],
        [false, false],
        [0, false],
        ['', false],
        [null, false],
        [[], false]
        ];
    }

    public function expectedWhetherToArrayReturnsDataAccordingToWhatTheApiRequests()
    {
        return [
        "Provider" => "Simulado",
        "Type" => "CreditCard",
        "Amount" => 10000,
        "Currency" => "BRL",
        "Country" => "BRA",
        "Installments" => 1,
        "Interest" => "ByMerchant",
        "Capture" => true,
        "Authenticate" => false,
        "Recurrent" => false,
        "SoftDescriptor" => "Mensagem",
        "DoSplit" => true,
        "CreditCard" => [
            "CardNumber" => "4111111111111111",
            "Holder" => "Nome do Portador",
            "ExpirationDate" => "12/2021",
            "SecurityCode" => "123",
            "Brand" => "Visa",
            "SaveCard" => false
        ],
        "ExtraDataCollection" => [
            [
            "Name" => "NomeDoCampo",
            "Value" => "ValorDoCampo"
            ]
        ],
        "FraudAnalysis" => [
            "Sequence" => "AnalyseFirst",
            "SequenceCriteria" => "OnSuccess",
            "Provider" => "Cybersource",
            "CaptureOnLowRisk" => true,
            "TotalOrderAmount" => 10000,
            "FingerPrintId" => "id123456",
            "Browser" => [
            "HostName" => "Teste",
            "CookiesAccepted" => false,
            "Email" => "comprador@braspag.com.br",
            "Type" => "Chrome",
            "IpAddress" => "127.0.0.1"
            ],
            "Cart" => [
            "ReturnsAccepted" => true,
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
            ],
            "MerchantDefinedFields" => [
            [
                "Id" => 2,
                "Value" => "100"
            ],
            [
                "Id" => 4,
                "Value" => "Web"
            ],
            [
                "Id" => 9,
                "Value" => "SIM"
            ]
            ],
            "Shipping" => [
            "Addressee" => "João das Couves",
            "Method" => "lowcost",
            "Phone" => "551121840540"
            ],
            "Id" => "0e4d0a3c-e424-4fa5-a573-4eabbd44da42",
            "Status" => 1,
            "FraudAnalysisReasonCode" => 100,
            "ReplyData" => [
            "AddressInfoCode" => "COR-BA^MM-BIN",
            "FactorCode" => "B^D^R^Z",
            "Score" => 42,
            "BinCountry" => "us",
            "CardIssuer" => "FIA CARD SERVICES, N.A.",
            "CardScheme" => "VisaCredit",
            "HostSeverity" => 1,
            "InternetInfoCode" => "FREE-EM^RISK-EM",
            "IpRoutingMethod" => "Undefined",
            "ScoreModelUsed" => "default_lac",
            "CasePriority" => 3,
            "ProviderTransactionId" => "5220688414326697303008"
            ]
        ]
        ];
    }
}
