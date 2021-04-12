<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Braspag\Split\Domains\Subordinate\Document;

final class DocumentTest extends TestCase
{
    /**
     * @dataProvider dataProviderCpfValid
     * @group ClassSubordinateDocument
     */
    public function testCreateInstanceWithCpfValid($arg)
    {
        $instance = Document::cpf($arg);
        $this->assertInstanceOf(Document::class, $instance);
    }

    /**
     * @dataProvider dataProviderCpfInvalid
     * @group ClassSubordinateDocument
     */
    public function testCreateInstanceWithCpfInvalid($arg)
    {
        $this->expectException(InvalidArgumentException::class);
        Document::cpf($arg);
    }

    /**
     * @dataProvider dataProviderCnpjValid
     * @group ClassSubordinateDocument
     */
    public function testCreateInstanceWithCnpjValid($arg)
    {
        $instance = Document::cnpj($arg);
        $this->assertInstanceOf(Document::class, $instance);
    }

    /**
     * @dataProvider dataProviderCnpjInvalid
     * @group ClassSubordinateDocument
     */
    public function testCreateInstanceWithCnpjInvalid($arg)
    {
        $this->expectException(InvalidArgumentException::class);
        Document::cnpj($arg);
    }

    /**
     * @group ClassSubordinateDocument
     */
    public function testConvertObjectToArrayWithCnpj()
    {
        $expected = [
        "DocumentNumber" => "96462142000140",
        "DocumentType" => "Cnpj"
        ];

        $instance = Document::cnpj("96462142000140");

        $this->assertEquals($expected, $instance->toArray());
    }

    /**
     * @group ClassSubordinateDocument
     */
    public function testConvertObjectToArrayWithCpf()
    {
        $expected = [
        "DocumentNumber" => "02501130073",
        "DocumentType" => "Cpf"
        ];

        $instance = Document::cpf("025.011.300-73");

        $this->assertEquals($expected, $instance->toArray());
    }

    /**
     * @group ClassSubordinateDocument
     */
    public function testPopulate()
    {
        $obj = json_decode('{
        "DocumentNumber": "96462142000140",
        "DocumentType": "Cnpj"
        }');

        $expected = [
        "DocumentNumber" => "96462142000140",
        "DocumentType" => "Cnpj"
        ];

        $instance = new Document;
        $instance->populate($obj);

        $this->assertEquals($expected, $instance->toArray());
    }

    public function dataProviderCpfValid()
    {
        return [
        ['342.444.198-88'],
        ['342.444.198.88'],
        ['350.45261819'],
        ['693-319-118-40'],
        ['3.6.8.8.9.2.5.5.4.8.8'],
        ['11598647644'],
        ['86734718697'],
        ['86223423284'],
        ['24845408333'],
        ['95574461102'],
        ];
    }

    public function dataProviderCpfInvalid()
    {
        return [
        [''],
        ['01234567890'],
        ['000.000.000-00'],
        ['111.222.444-05'],
        ['999999999.99'],
        ['8.8.8.8.8.8.8.8.8.8.8'],
        ['693-319-110-40'],
        ['698.111-111.00'],
        ['11111111111'],
        ['22222222222'],
        ['12345678900'],
        ['99299929384'],
        ['84434895894'],
        ['44242340000'],
        ['1'],
        ['22'],
        ['123'],
        ['992999999999929384'],
        ];
    }

    public function dataProviderCnpjValid()
    {
        return [
        ['32.063.364/0001-07'],
        ['24.663.454/0001-00'],
        ['57.535.083/0001-30'],
        ['24.760.428/0001-09'],
        ['27.355.204/0001-00'],
        ['36.310.327/0001-07'],
        ['38175021000110'],
        ['37550610000179'],
        ['12774546000189'],
        ['77456211000168'],
        ['02023077000102'],
        ];
    }

    public function dataProviderCnpjInvalid()
    {
        return [
        ['12.345.678/9012-34'],
        ['11.111.111/1111-11'],
        ['00000000000000'],
        ['11111111111111'],
        ['22222222222222'],
        ['33333333333333'],
        ['44444444444444'],
        ['55555555555555'],
        ['66666666666666'],
        ['77777777777777'],
        ['88888888888888'],
        ['99999999999999'],
        ['12345678900123'],
        ['99299929384987'],
        ['84434895894444'],
        ['44242340000000'],
        ['1'],
        ['22'],
        ['123'],
        ['992999999999929384'],
        ['99-010-0.'],
        ];
    }
}
