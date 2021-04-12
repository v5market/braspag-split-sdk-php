<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Sale\Sale;
use Braspag\Split\Domains\Sale\Customer;
use Braspag\Split\Domains\Sale\Payment;

class SaleTest extends TestCase
{
    /**
     * @group ClassSale
     */
    public function testCreateNewInstance()
    {
        $instance = new Sale;
        $this->assertInstanceOf(Sale::class, $instance);
    }

    /**
     * @group ClassSale
     */
    public function testSetterMerchantOrderIdInConstructWithValidArgument()
    {
        $merchantOrderId = '2020062201';
        $instance = new Sale($merchantOrderId);

        $this->assertEquals($merchantOrderId, $instance->getMerchantOrderId());
    }

    /**
     * @group ClassSale
     */
    public function testSetterMerchantOrderIdWithValidArgument()
    {
        $merchantOrderId = '2020062202';
        $instance = new Sale;
        $instance->setMerchantOrderId($merchantOrderId);

        $this->assertEquals($merchantOrderId, $instance->getMerchantOrderId());
    }

    /**
     * @group ClassSale
     */
    public function testSetterMerchantOrderIDtooLongShouldGiveError()
    {
        $this->expectException(LengthException::class);
        $merchantOrderId = str_repeat('v', 51);

        $instance = new Sale;
        $instance->setMerchantOrderId($merchantOrderId);
    }

    /**
     * @group ClassSale
     */
    public function testSetterCustomerWithValidArgument()
    {
        $customer = new Customer;
        $instance = new Sale;
        $instance->setCustomer($customer);

        $this->assertEquals($customer, $instance->getCustomer());
    }

    /**
     * @group ClassSale
     */
    public function testSetterPaymentWithValidArgument()
    {
        $payment = new Payment;
        $instance = new Sale;
        $instance->setPayment($payment);

        $this->assertEquals($payment, $instance->getPayment());
    }

    /**
     * @group ClassSale
     */
    public function testPaymentWithoutDataShouldGiveErrorWithMethodToArray()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Sale;
        $instance->toArray();
    }

    /**
     * @group ClassSale
     */
    public function testPopulateMethodWithValidArgument()
    {
        $json = '{"MerchantOrderId":"2020062203","Customer":{"Name":"Nome do Comprador","Identity":"46133873027","IdentityType":"Cpf","Email":"comprador@braspag.com.br","Birthdate":"1991-01-02","Phone":"5521976781114","Address":{"Street":"Alameda Xingu","Number":"512","Complement":"27 andar","City":"S\u00e3o Paulo","State":"SP","ZipCode":"12345987","Country":"BR","District":"Alphaville"},"DeliveryAddress":{"Street":"Alameda Xingu","Number":"512","Complement":"27 andar","City":"S\u00e3o Paulo","State":"SP","ZipCode":"12345987","Country":"BR","District":"Alphaville"}},"Payment":{"Provider":"Simulado","Type":"CreditCard","Amount":10000,"Currency":"BRL","Country":"BRA","Installments":1,"Interest":"ByMerchant","Capture":true,"SoftDescriptor":"Mensagem","DoSplit":true,"ExtraDataCollection":[{"Name":"NomeDoCampo","Value":"ValorDoCampo"}],"CreditCard":{"CardNumber":"455187******0181","Holder":"Nome do Portador","ExpirationDate":"12\/2021","SecurityCode":"123","Brand":"Visa","SaveCard":false},"FraudAnalysis":{"CaptureOnLowRisk":true,"Id":"0e4d0a3c-e424-4fa5-a573-4eabbd44da42","Status":1,"Sequence":"AnalyseFirst","SequenceCriteria":"OnSuccess","Provider":"Cybersource","TotalOrderAmount":10000,"FingerPrintId":"id123456","Browser":{"HostName":"Teste","CookiesAccepted":false,"Email":"comprador@braspag.com.br","Type":"Chrome","IpAddress":"127.0.0.1"},"Cart":{"ReturnsAccepted":true,"Items":[{"GiftCategory":"Undefined","HostHedge":"Off","NonSensicalHedge":"Off","ObscenitiesHedge":"Off","PhoneHedge":"Off","Name":"ItemTeste1","Quantity":1,"Sku":"20170511","UnitPrice":10000,"Risk":"High","TimeHedge":"Normal","Type":"AdultContent","VelocityHedge":"High"}]},"MerchantDefinedFields":[{"Id":2,"Value":"100"},{"Id":4,"Value":"Web"},{"Id":9,"Value":"SIM"}],"Shipping":{"Addressee":"Jo\u00e3o das Couves","Method":"LowCost","Phone":"551121840540"},"FraudAnalysisReasonCode":100,"ReplyData":{"AddressInfoCode":"COR-BA^MM-BIN","FactorCode":"B^D^R^Z","Score":42,"BinCountry":"us","CardIssuer":"FIA CARD SERVICES, N.A.","CardScheme":"VisaCredit","HostSeverity":1,"InternetInfoCode":"FREE-EM^RISK-EM","IpRoutingMethod":"Undefined","ScoreModelUsed":"default_lac","CasePriority":3,"ProviderTransactionId":"5220688414326697303008"}},"PaymentId":"c374099e-c474-4916-9f5c-f2598fec2925","ProofOfSale":"20170510053219433","AcquirerTransactionId":"0510053219433","AuthorizationCode":"936403","ReceivedDate":"2017-05-10 17:32:19","CapturedDate":"2017-05-10 17:32:19","CapturedAmount":10000,"ReasonMessage":"Successful","Status":2,"ProviderReturnCode":"6","ProviderReturnMessage":"Operation Successful"}}';
        $instance = new Sale;
        $instance->populate(json_decode($json));

        $this->assertEqualsIgnoringCase($this->expectedPopulateMethodWithValidArgument(), $instance->toArray());
    }

    public function expectedPopulateMethodWithValidArgument()
    {
        return [
        'MerchantOrderId' => '2020062203',
        'Customer' => [
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
        ],
        'Payment' => [
            "Provider" => "Simulado",
            "Type" => "CreditCard",
            "Amount" => 10000,
            "Currency" => "BRL",
            "Country" => "BRA",
            "Installments" => 1,
            "Interest" => "ByMerchant",
            "Capture" => true,
            "SoftDescriptor" => "Mensagem",
            "DoSplit" => true,
            "CreditCard" => [
            "CardNumber" => "455187******0181",
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
                    "GiftCategory" => "undefined",
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
                "Method" => "LowCost",
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
            ],
            "PaymentId" => "c374099e-c474-4916-9f5c-f2598fec2925",
            "ProofOfSale" => "20170510053219433",
            "AcquirerTransactionId" => "0510053219433",
            "AuthorizationCode" => "936403",
            "ReceivedDate" => "2017-05-10 17:32:19",
            "CapturedDate" => "2017-05-10 17:32:19",
            "CapturedAmount" => 10000,
            "ReasonMessage" => "Successful",
            "Status" => 2,
            "ProviderReturnCode" => "6",
            "ProviderReturnMessage" => "Operation Successful"
        ]
        ];
    }
}
