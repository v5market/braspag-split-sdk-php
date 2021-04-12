<?php

namespace Braspag\Split\Domains\Subordinate;

use Braspag\Split\Validation\Validator;
use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Exception\FileNotFoundException;
use Braspag\Split\Constants\Subordinate\Attachment as Constants;

class Attachment implements BraspagSplit
{
    public const FILESIZE_MAX = 1024 * 1024;

    /** @var string ProofOfBankDomicile ou ModelOfAdhesionTerm */
    private $type;

    /** @var string */
    private $filename;

    /** @var string Permitido: pdf, png, jpg e jpeg */
    private $filetype;

    /** @var string Conteúdo do arquivo encodado com base64 */
    private $data;

    /**
     * @param string $value {@see Braspag\Split\Constants\Subordinate\Attachment}
     */
    public function setType(string $value)
    {
        if (!in_array($value, Constants::TYPES)) {
            throw new \InvalidArgumentException('Type invalid. The type must be ' .
            'Braspag\Split\Constants\Subordinate\Attachment::TYPE_*. Was: ' . $value, 10000);
        }

        $this->type = $value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $value
     */
    public function setFilename(string $value)
    {
        if (mb_strlen($value) > 50) {
            throw new \LengthException('Filename must be less than 51 characters', 10001);
        }

        $extension = pathinfo($value, PATHINFO_EXTENSION);
        if ($extension) {
            $this->setFileType($extension);
        }
        $this->filename = $value;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $value Ver permitidos em self::ALLOWED_EXTENSIONS
     */
    public function setFileType(string $value)
    {
        if (!in_array($value, Constants::ALLOWED_EXTENSIONS)) {
            throw new \OutOfBoundsException("Extension $value is invalid. Use: " .
            implode(', ', Constants::ALLOWED_EXTENSIONS), 10002);
        }

        $this->filetype = $value;
    }

    /**
     * @return string
     */
    public function getFileType()
    {
        return $this->filetype;
    }

    /**
     * @param string $value Caminho do arquivo ou código base64
     *
     * @throws FileNotFoundException quando informar o caminho de um arquivo e ele
     *  não existir
     */
    public function setData(string $value)
    {
        if (file_exists($value) && is_file($value)) {
            if (filesize($value) > self::FILESIZE_MAX) {
                throw new \InvalidArgumentException('The file must have a maximum of 1MB', 10003);
            }

            $value = $this->convertTobase($value);
        }

        if (!Validator::base64()->validator($value)) {
            throw new FileNotFoundException('The file or data is not valid. Use base64', 10004);
        }

        $this->data = $value;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Converte um arquivo para Base64
     *
     * @param string $value Caminho do arquivo
     *
     * @return string Retorna arquivo convertido em Base64
     */
    private function convertToBase(string $value)
    {
        return base64_encode(file_get_contents($value));
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $file = [
            "Name" => $this->getFilename(),
            "FileType" => $this->getFileType(),
        ];

        if ($this->data) {
            $file["Data"] = $this->data;
        }

        return [
            "AttachmentType" => $this->getType(),
            "File" => $file
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->AttachmentType)) {
            $this->setType($data->AttachmentType);
        }

        if (!isset($data->File)) {
            return;
        }

        $file = $data->File;

        if (isset($file->Name)) {
            $this->setFilename($file->Name);
        }

        if (isset($file->FileType)) {
            $this->setFileType($file->FileType);
        }

        if (isset($file->Data)) {
            $this->setData($file->Data);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
