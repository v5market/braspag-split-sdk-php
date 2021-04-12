<?php

namespace Braspag\Split\Domains\Subordinate;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Constants\Subordinate\Bank as Constants;

class Bank implements BraspagSplit
{
    private $bank;
    private $bankAccountType;
    private $number;
    private $operation;
    private $verifierDigit;
    private $agencyNumber;
    private $agencyDigit = 'x';
    private $document;

    /**
     * Define o código do banco
     * A lista pode ser encontrada em
     * https://www.bcb.gov.br/Fis/CODCOMPE/Tabela.pdf
     *
     * @param string $value
     */
    public function setBank(string $value)
    {
        if (empty($value) || !preg_match('/^\d{1,3}$/', $value)) {
            throw new \InvalidArgumentException(
                'Bank invalid. See https://www.bcb.gov.br/Fis/CODCOMPE/Tabela.pdf',
                11000
            );
        }

        $this->bank = $value;
    }

    /**
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Define o tipo da conta
     * Conta corrente ou Poupança
     * Ver opções em Braspag\Split\Contants\Subordinate\Bank::ACCOUNT_TYPE
     *
     * @param string $value
     */
    public function setBankAccountType(string $value)
    {
        if (!in_array($value, Constants::ACCOUNT_TYPES)) {
            throw new \InvalidArgumentException('Account type invalid. ' .
            'Use Braspag\Split\Contants\Subordinate\Bank::ACCOUNT_TYPE_*', 11001);
        }

        $this->bankAccountType = $value;
    }

    /**
     * @return string
     */
    public function getBankAccountType()
    {
        return $this->bankAccountType;
    }

    /**
     * @param string $value
     */
    public function setNumber(string $value)
    {
        if (empty($value) || !preg_match('/^\d{1,20}$/', $value)) {
            throw new \InvalidArgumentException("Bank number invalid. Was: $value", 11002);
        }

        $this->number = $value;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $value
     */
    public function setOperation(string $value)
    {
        if (mb_strlen($value) > 10) {
            throw new \LengthException('Bank operation must be less than 11 characters', 11003);
        }

        $this->operation = $value;
    }

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param string $value
     */
    public function setVerifierDigit(string $value)
    {
        if (!preg_match('/^\d{1}$/', $value)) {
            throw new \InvalidArgumentException("Bank number verifier digit invalid. Was: $value", 11004);
        }

        $this->verifierDigit = $value;
    }

    /**
     * @return string
     */
    public function getVerifierDigit()
    {
        return $this->verifierDigit;
    }

    /**
     * @param string $value
     */
    public function setAgencyNumber(string $value)
    {
        if (empty($value) || strlen($value) > 15) {
            throw new \LengthException("Agency number must be less than 16 characters. Was: $value", 11005);
        }

        $this->agencyNumber = $value;
    }

    /**
     * @return string
     */
    public function getAgencyNumber()
    {
        return $this->agencyNumber;
    }

    /**
     * @param string $value
     */
    public function setAgencyDigit(string $value = 'x')
    {
        if (empty($value)) {
            $value = 'x';
        }

        if (!preg_match('/^[\dx]$/i', $value)) {
            throw new \InvalidArgumentException("Agency Digit is required and must be numeric. Was: $value", 11006);
        }

        $this->agencyDigit = $value;
    }

    /**
     * @return string
     */
    public function getAgencyDigit()
    {
        return !empty($this->agencyDigit) ? $this->agencyDigit : 'x';
    }

    /**
     * @return string
     */
    public function getAgencyFull()
    {
        return "$this->agencyNumber-" . $this->getAgencyDigit();
    }

    /**
     * @param Document $value
     */
    public function setDocument(Document $value)
    {
        $this->document = $value;
    }

    /**
     * @return Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $document = $this->document
            ? $this->document->toArray()
            : [];

        $operation = $this->operation
            ? ["Operation" => $this->operation]
            : [];

        return array_merge([
            "Bank" => $this->bank,
            "BankAccountType" => $this->bankAccountType,
            "Number" => $this->number,
            "VerifierDigit" => $this->verifierDigit,
            "AgencyNumber" => $this->agencyNumber,
            "AgencyDigit" => $this->getAgencyDigit()
        ], $document, $operation);
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->Bank)) {
            $this->setBank($data->Bank);
        }

        if (isset($data->BankAccountType)) {
            $this->setBankAccountType($data->BankAccountType);
        }

        if (isset($data->Number)) {
            $this->setNumber($data->Number);
        }

        if (isset($data->Operation)) {
            $this->setOperation($data->Operation);
        }

        if (isset($data->VerifierDigit)) {
            $this->setVerifierDigit($data->VerifierDigit);
        }

        if (isset($data->AgencyNumber)) {
            $this->setAgencyNumber($data->AgencyNumber);
        }

        if (isset($data->AgencyDigit)) {
            $this->setAgencyDigit($data->AgencyDigit);
        }

        if (isset($data->DocumentNumber) && isset($data->DocumentType)) {
            if (strtolower($data->DocumentType) === 'cnpj') {
                $document = Document::cnpj($data->DocumentNumber);
            } else {
                $document = Document::cpf($data->DocumentNumber);
            }

            $this->setDocument($document);
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
