<?php

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Environment;
use \Braspag\Split\Domains\FraudAnalysis\FraudAnalysis;
use \Braspag\Split\Domains\FraudAnalysis\Browser;
use \Braspag\Split\Domains\FraudAnalysis\ReplyData;
use \Braspag\Split\Domains\Cart\Cart;
use \Braspag\Split\Domains\Cart\Item as CartItem;

class FraudAnalysisTest extends TestCase
{
    /**
     * @group ClassFraudAnalysis
     */
    public function testCreateInstance()
    {
        $instance = new FraudAnalysis;
        $this->assertInstanceOf(FraudAnalysis::class, $instance);
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testSequenceWithValidArguments()
    {
        $instance = new FraudAnalysis;
        $instance->setSequence('AnalyseFirst');

        $this->assertEquals('AnalyseFirst', $instance->getSequence());
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testSequenceWithInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new FraudAnalysis;
        $instance->setSequence('Invalid');
    }

    /**
     * @group ClassFraudAnalysis
     * @dataProvider providerConvertToBoolean
     */
    public function testSetterCaptureOnLowRiskMustConverterToBoolean($arg, $expected)
    {
        $instance = new FraudAnalysis;
        $instance->setCaptureOnLowRisk($arg);

        $this->assertTrue($instance->getCaptureOnLowRisk() === $expected);
    }

    /**
     * @group ClassFraudAnalysis
     * @dataProvider providerConvertToBoolean
     */
    public function testSetterVoidOnHighRiskMustConverterToBoolean($arg, $expected)
    {
        $instance = new FraudAnalysis;
        $instance->setVoidOnHighRisk($arg);

        $this->assertTrue($instance->getVoidOnHighRisk() === $expected);
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testSetterTotalOrderAmountMustToBeInteger()
    {
        $instance = new FraudAnalysis;
        $instance->setTotalOrderAmount(130793);

        $this->assertEquals(130793, $instance->getTotalOrderAmount());
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testSetterCartThereShouldBeNoErrors()
    {
        $cartItems = [
        new CartItem,
        new CartItem
        ];

        $cart = new Cart;
        $cart->setIsGift(true);
        $cart->setReturnsAccepted(false);
        $cart->setItems($cartItems);

        $instance = new FraudAnalysis;
        $instance->setCart($cart);

        $this->assertEquals($cartItems, $instance->getCart()->getItems());
        $this->assertContains($cartItems[0], $instance->getCart()->getItems());
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testsIfSetterMerchantDefinedFieldsWithEmptyIdWillGiveError()
    {
        $this->expectException(TypeError::class);
        $instance = new FraudAnalysis;
        $instance->addMerchantDefinedFields(null, '314');
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testsWhetherTheOrgidIsReturningTheCorrectValueDependingOnTheEnvironment()
    {
        $expected = [
        '1snn5n9w',
        'k8vif92e'
        ];

        $current = [
        FraudAnalysis::orgId(Environment::sandbox('clientId', 'clientSecret')),
        FraudAnalysis::orgId(Environment::production('clientId', 'clientSecret')),
        ];

        $this->assertEquals($expected, $current);
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testSetterReplyDataWithValidArgument()
    {
        $expected = '{"AddressInfoCode":"COR-BA^MM-BIN","FactorCode":"B^D^R^Z","Score":42,"BinCountry":"us","CardIssuer":"FIA CARD SERVICES, N.A.","CardScheme":"VisaCredit","HostSeverity":1,"InternetInfoCode":"FREE-EM^RISK-EM","IpRoutingMethod":"Undefined","ScoreModelUsed":"default_lac","CasePriority":3,"ProviderTransactionId":"5220688414326697303008"}';

        $replyData = new ReplyData;
        $replyData->populate(json_decode($expected));

        $instance = new FraudAnalysis;
        $instance->setReplyData($replyData);

        $this->assertEquals($replyData->toArray(), $instance->getReplyData()->toArray());
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testSetterFraudAnalysisReasonCodeWithValidArgument()
    {
        $instance = new FraudAnalysis;
        $instance->setFraudAnalysisReasonCode(400);

        $this->assertEquals(400, $instance->getFraudAnalysisReasonCode());
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testWhetherToArrayReturnsDataAccordingToWhatTheApiRequests()
    {
        $expected = [
        "Sequence" => "AnalyseFirst",
        "Provider" => "Cybersource",
        "CaptureOnLowRisk" => true,
        "TotalOrderAmount" => 10000,
        "FingerPrintId" => "id123456",
        "Browser" => [
            "CookiesAccepted" => false,
            "Email" => "comprador@braspag.com.br",
            "HostName" => "Teste",
            "IpAddress" => "127.0.0.1",
            "Type" => "Chrome",
        ],
        "Cart" => [
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
                "VelocityHedge" => "high",
            ]
            ]
        ],
        "SequenceCriteria" => "OnSuccess"
        ];

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
        $cart->addItem($cartItem);

        $browser = new Browser;
        $browser->setCookiesAccepted(false);
        $browser->setEmail('comprador@braspag.com.br');
        $browser->setHostName('Teste');
        $browser->setIpAddress('127.0.0.1');
        $browser->setType('Chrome');

        $fraudAnalysis = new FraudAnalysis;
        $fraudAnalysis->setSequence('AnalyseFirst');
        $fraudAnalysis->setProvider('Cybersource');
        $fraudAnalysis->setSequenceCriteria('OnSuccess');
        $fraudAnalysis->setCaptureOnLowRisk(true);
        $fraudAnalysis->setTotalOrderAmount(10000);
        $fraudAnalysis->setFingerPrintId('id123456');
        $fraudAnalysis->setBrowser($browser);
        $fraudAnalysis->setCart($cart);

        $this->assertEquals($expected, $fraudAnalysis->toArray());
    }

    /**
     * @group ClassFraudAnalysis
     */
    public function testPopulateMethodShouldHaveNoErrors()
    {
        $json = '{"Id":"0e4d0a3c-e424-4fa5-a573-4eabbd44da42","Status":1,"Sequence":"AnalyseFirst","SequenceCriteria":"OnSuccess","Provider":"Cybersource","TotalOrderAmount":10000,"FingerPrintId":"074c1ee676ed4998ab66491013c565e2","Browser":{"HostName":"Teste","CookiesAccepted":false,"Email":"comprador@braspag.com.br","Type":"Chrome","IpAddress":"127.0.0.1"},"Cart":{"IsGift":false,"ReturnsAccepted":true,"Items":[{"GiftCategory":"undefined","HostHedge":"off","NonSensicalHedge":"off","ObscenitiesHedge":"off","PhoneHedge":"off","Name":"ItemTeste1","Quantity":1,"Sku":"20170511","UnitPrice":10000,"Risk":"high","TimeHedge":"normal","Type":"adultcontent","VelocityHedge":"high"},{"GiftCategory":"undefined","HostHedge":"off","NonSensicalHedge":"off","ObscenitiesHedge":"off","PhoneHedge":"off","Name":"ItemTeste2","Quantity":1,"Sku":"20170512","UnitPrice":10000,"Risk":"high","TimeHedge":"normal","Type":"adultcontent","VelocityHedge":"high"}]},"MerchantDefinedFields":[{"Id":2,"Value":"100"},{"Id":4,"Value":"Web"},{"Id":9,"Value":"SIM"}],"Shipping":{"Addressee":"o\u00e3o das Couves","Method":"LowCost","Phone":"551121840540"},"FraudAnalysisReasonCode":100,"ReplyData":{"AddressInfoCode":"COR-BA^MM-BIN","FactorCode":"B^D^R^Z","Score":42,"BinCountry":"us","CardIssuer":"FIA CARD SERVICES, N.A.","CardScheme":"VisaCredit","HostSeverity":1,"InternetInfoCode":"FREE-EM^RISK-EM","IpRoutingMethod":"undefined","ScoreModelUsed":"default_lac","CasePriority":3,"ProviderTransactionId":"5220688414326697303008"}}';

        $instance = new FraudAnalysis;
        $instance->populate(json_decode($json));

        $this->assertEquals($json, json_encode($instance));
    }

    public function providerConvertToBoolean()
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
}
