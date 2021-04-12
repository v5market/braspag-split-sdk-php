<?php

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\FraudAnalysis\ReplyData;

class ReplyDataTest extends TestCase
{
    /**
     * @group ClassReplyData
     */
    public function testCreateNewInstance()
    {
        $instance = new ReplyData;
        $this->assertInstanceOf(ReplyData::class, $instance);
    }

    /**
     * @group ClassReplyData
     */
    public function testPopulateMethodShouldHaveNoErrors()
    {
        $expected = '{"AddressInfoCode":"COR-BA^MM-BIN","FactorCode":"B^D^R^Z","Score":42,"BinCountry":"us","CardIssuer":"FIA CARD SERVICES, N.A.","CardScheme":"VisaCredit","HostSeverity":1,"InternetInfoCode":"FREE-EM^RISK-EM","IpRoutingMethod":"Undefined","ScoreModelUsed":"default_lac","CasePriority":3,"ProviderTransactionId":"5220688414326697303008"}';

        $replyData = new ReplyData;
        $replyData->populate(json_decode($expected));

        $this->assertEqualsIgnoringCase($expected, json_encode($replyData));
    }
}
