<?php
declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \Braspag\Split\Domains\Subordinate\Attachment;
use \Braspag\Split\Constants\Subordinate\Attachment as Constants;

class AttachmentTest extends TestCase
{
    /**
     * @group ClassSubordinateAttachment
     */
    public function testCreateInstance()
    {
        $instance = new Attachment;
        $this->assertInstanceOf(Attachment::class, $instance);
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testSetterTypeProofOfBankDomicileMustBeValid()
    {
        $instance = new Attachment;
        $instance->setType(Constants::TYPE_PROOF_OF_BANK_DOMICILE);

        $this->assertEquals('ProofOfBankDomicile', $instance->getType());
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testSetterTypeModelOfAdhesionTermMustBeValid()
    {
        $instance = new Attachment;
        $instance->setType(Constants::TYPE_MODEL_OF_ADHESION_TERM);

        $this->assertEquals('ModelOfAdhesionTerm', $instance->getType());
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testFilenameValid()
    {
        $filename = 'comprovante.jpg';

        $instance = new Attachment;
        $instance->setFilename($filename);

        $this->assertEquals($filename, $instance->getFilename());
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testFilenameInvalid()
    {
        $this->expectException(LengthException::class);
        $filename = str_repeat('V', 51);

        $instance = new Attachment;
        $instance->setFilename($filename);
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testSetterFilenameAndSetterExtensionValid()
    {
        $extension = 'jpg';
        $filename = "comprovante.$extension";

        $instance = new Attachment;
        $instance->setFilename($filename);

        $this->assertEquals($extension, $instance->getFileType());
    }

    /**
     * @group ClassSubordinateAttachment
     * @dataProvider providerExtensionValid
     */
    public function testSetterAndGetterExtensionWithArgValid($arg)
    {
        $instance = new Attachment;
        $instance->setFileType($arg);

        $this->assertEquals($instance->getFileType(), $arg);
    }

    /**
     * @group ClassSubordinateAttachment
     * @dataProvider providerExtensionInvalid
     */
    public function testSetterAndGetterExtensionWithArgInvalid($arg)
    {
        $this->expectException(OutOfBoundsException::class);

        $instance = new Attachment;
        $instance->setFileType($arg);
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testSetterAndGetterExtensionWithPathValid()
    {
        $path = __DIR__ . '/../Validation/Data/image.png';
        $expected = file_get_contents(__DIR__ . '/../Validation/Data/base64_valid.txt');

        $instance = new Attachment;
        $instance->setData($path);

        $this->assertEquals($expected, $instance->getData());
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testSetterAndGetterExtensionWithPathNonexistent()
    {
        $this->expectException(\Braspag\Split\Exception\FileNotFoundException::class);
        $path = __DIR__ . '/../Validation/Data/notfound.png';

        $instance = new Attachment;
        $instance->setData($path);
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testMethodToArrayUsingFullFilePath()
    {
        $path = __DIR__ . '/../Validation/Data/image.png';
        $expected = [
        "AttachmentType" => 'ProofOfBankDomicile',
        "File" => [
            "Name" => "image.png",
            "FileType" => "png",
            "Data" => file_get_contents(__DIR__ . '/../Validation/Data/base64_valid.txt')
        ]
        ];

        $instance = new Attachment;
        $instance->setType(Constants::TYPE_PROOF_OF_BANK_DOMICILE);
        $instance->setFilename('image.png');
        $instance->setFileType('png');
        $instance->setData($path);

        $this->assertEquals($expected, $instance->toArray());
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testMethodToArrayUsingBase64()
    {
        $data = file_get_contents(__DIR__ . '/../Validation/Data/base64_valid.txt');

        $expected = [
        "AttachmentType" => 'ModelOfAdhesionTerm',
        "File" => [
            "Name" => "image.png",
            "FileType" => "png",
            "Data" => $data
        ]
        ];

        $instance = new Attachment;
        $instance->setType(Constants::TYPE_MODEL_OF_ADHESION_TERM);
        $instance->setFilename('image.png');
        $instance->setFileType('png');
        $instance->setData($data);

        $this->assertEquals($expected, $instance->toArray());
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testWithFileLargerThanOneMegabytesShouldGiveError()
    {
        $this->expectException(InvalidArgumentException::class);

        $instance = new Attachment;
        $instance->setData(__DIR__ . '/../../data/ftfgc6x9my751.png');
    }

    /**
     * @group ClassSubordinateAttachment
     */
    public function testConvertJsonToObject()
    {
        $json = json_decode('{
        "AttachmentType": "ProofOfBankDomicile",
        "File": {
            "Name": "comprovante",
            "FileType": "png"
        }
        }');

        $expected = [
        "AttachmentType" => 'ProofOfBankDomicile',
        "File" => [
            "Name" => "comprovante",
            "FileType" => "png"
        ]
        ];

        $instance = new Attachment;
        $instance->populate($json);

        $this->assertEquals($expected, $instance->toArray());
    }

    public function providerExtensionValid()
    {
        return [
        ['pdf'],
        ['png'],
        ['jpeg'],
        ['jpg'],
        ];
    }

    public function providerExtensionInvalid()
    {
        return [
        ['exe'],
        ['webp'],
        ['bitmap'],
        ['docx'],
        ['doc'],
        ['sh'],
        ];
    }
}
