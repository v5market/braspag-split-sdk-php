<?php

namespace Braspag\Split\Domains\Subordinate;

use Braspag\Split\Validation\Validator;
use Braspag\Split\Domains\Address;
use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Request\Response;

class Merchant implements BraspagSplit
{
    use Response;

    private $masterMerchantId;
    private $merchantId;
    private $corporateName;
    private $fancyName;
    private $document;
    private $merchantCategoryCode;
    private $contactName;
    private $contactPhone;
    private $mailAddress;
    private $website;
    private $blocked;
    private $analysis;
    private $bankAccount;
    private $address;
    private $agreement;
    private $notification;
    private $attachments = [];

    /**
     * ClientId do Master Responsável pelo Subordinado
     *
     * @param string $value
     */
    public function setMasterMerchantId(string $value)
    {
        if (mb_strlen($value) > 36) {
            throw new \LengthException('The value must be less than 37 characters. ' .
            'Was: ' . mb_strlen($value) . ' characteres', 12000);
        }

        $this->masterMerchantId = $value;
    }

    /**
     * @return string
     */
    public function getMasterMerchantId()
    {
        return $this->masterMerchantId;
    }

    /**
     * Identificador da loja na Braspag
     *
     * @param string $value
     */
    public function setMerchantId(string $value)
    {
        if (mb_strlen($value) > 36) {
            throw new \LengthException('The value must be less than 37 characters. ' .
            'Was: ' . mb_strlen($value) . ' characteres', 12001);
        }

        $this->merchantId = $value;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Razão social
     *
     * @param string $value
     */
    public function setCorporateName(string $value)
    {
        if (mb_strlen($value) > 100) {
            throw new \LengthException('The value must be less than 100 characters. ' .
            'Was: ' . mb_strlen($value) . ' characteres', 12002);
        }

        $this->corporateName = $value;
    }

    /**
     * @return string
     */
    public function getCorporateName()
    {
        return $this->corporateName;
    }

    /**
     * Nome fantasia
     *
     * @param string $value
     */
    public function setFancyName(string $value)
    {
        if (mb_strlen($value) > 50) {
            throw new \LengthException('The value must be less than 51 characters. ' .
            'Was: ' . mb_strlen($value) . ' characters', 12003);
        }

        $this->fancyName = $value;
    }

    /**
     * @return string
     */
    public function getFancyName()
    {
        return $this->fancyName;
    }

    /**
     * Define documento do subordinado (CPF ou CNPJ)
     *
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
     * (MCC) número registrado na ISO 18245 para serviços financeiros de varejo,
     * utilizado para classificar o negócio pelo tipo fornecido de bens ou serviços.
     * https://www.web-payment-software.com/online-merchant-accounts/mcc-codes/
     *
     * @param string $value
     */
    public function setMerchantCategoryCode(string $value)
    {
        if (!preg_match('/^\d{1,4}$/', $value)) {
            throw new \InvalidArgumentException('Merchant category code is invalid. ' .
            'See https://www.web-payment-software.com/online-merchant-accounts/mcc-codes/', 12004);
        }

        $this->merchantCategoryCode = $value;
    }

    /**
     * @return string Retorna MCC https://www.web-payment-software.com/online-merchant-accounts/mcc-codes/
     */
    public function getMerchantCategoryCode()
    {
        return $this->merchantCategoryCode;
    }

    /**
     * Nome do contato responsável
     *
     * @param string $value
     */
    public function setContactName(string $value)
    {
        if (mb_strlen($value) > 100) {
            throw new \LengthException('The value must be less than 101 characters. ' .
            'Was: ' . mb_strlen($value) . ' characteres', 12005);
        }

        $this->contactName = $value;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Número do telefone do contato responsável
     *
     * @param string $value Apenas números
     */
    public function setContactPhone(string $value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (mb_strlen($value) > 11) {
            throw new \LengthException('The value must be less than 12 characters. ' .
            'Was: ' . mb_strlen($value) . ' characteres', 12006);
        }

        $this->contactPhone = $value;
    }

    /**
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * Endereço de e-mail
     *
     * @param string $value
     */
    public function setMailAddress(string $value)
    {
        if (!Validator::email()->validator($value)) {
            throw new \InvalidArgumentException('The mail address is invalid', 12007);
        }

        $this->mailAddress = $value;
    }

    /**
     * @return string
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }

    /**
     * Endereço do website
     *
     * @param string $value
     */
    public function setWebsite(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL) || !preg_match('/^https?\:\/\//', $value)) {
            throw new \InvalidArgumentException('The website is invalid', 12008);
        }

        $this->website = $value;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Flag para indicar se o subordinado está bloqueado para participar da transação
     *
     * @param mixed $value Se não for booleano, converte-o
     */
    public function setBlocked($value)
    {
        $this->blocked = !!$value;
    }

    /**
     * @return boolean
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Define as regras da análise (retorno da API)
     *
     * @param Analysis $value
     */
    public function setAnalysis(Analysis $value)
    {
        $this->analysis = $value;
    }

    /**
     * @return Analysis
     */
    public function getAnalysis()
    {
        return $this->analysis;
    }

    /**
     * Informação da conta bancária
     *
     * @param Bank $value
     */
    public function setBankAccount(Bank $value)
    {
        $this->bankAccount = $value;
    }

    /**
     * @return Bank
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * Informações de endereço
     *
     * @param Address $value
     */
    public function setAddress(Address $value)
    {
        $this->address = $value;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Define regras de taxas/juros
     *
     * @param Agreement $value
     */
    public function setAgreement(Agreement $value)
    {
        $this->agreement = $value;
    }

    /**
     * @return Agreement
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    /**
     * Configuração para retorno das notificações
     *
     * @param Notification $value
     */
    public function setNotification(Notification $value)
    {
        $this->notification = $value;
    }

    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Adiciona comprovantes de endereço
     *
     * @param Attachment $value
     */
    public function addAttachment(Attachment $value)
    {
        $this->attachments[] = $value;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $result = [];

        $result['MasterMerchantId'] = $this->masterMerchantId;
        $result['MerchantId'] = $this->merchantId;
        $result['CorporateName'] = $this->corporateName;
        $result['FancyName'] = $this->fancyName;
        $result['MerchantCategoryCode'] = $this->merchantCategoryCode;
        $result['ContactName'] = $this->contactName;
        $result['ContactPhone'] = $this->contactPhone;
        $result['MailAddress'] = $this->mailAddress;
        $result['Website'] = $this->website;
        $result['Blocked'] = $this->blocked;

        $result = array_merge($result, $this->document->toArray());
        $result['Analysis'] = $this->analysis ? $this->analysis->toArray() : null;
        $result['BankAccount'] = $this->bankAccount->toArray();
        $result['Address'] = $this->address->toArray();
        $result['Agreement'] = $this->agreement->toArray();
        $result['Notification'] = $this->notification->toArray();

        $result['Attachments'] = array_map(function ($arr) {
            return $arr->toArray();
        }, $this->attachments);

        return array_filter($result);
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->MasterMerchantId)) {
            $this->masterMerchantId = $data->MasterMerchantId;
        }

        if (isset($data->MerchantId)) {
            $this->merchantId = $data->MerchantId;
        }

        if (isset($data->CorporateName)) {
            $this->corporateName = $data->CorporateName;
        }

        if (isset($data->FancyName)) {
            $this->fancyName = $data->FancyName;
        }

        if (isset($data->DocumentNumber) && isset($data->DocumentType)) {
            $document = new Document();
            $document->populate($data);
            $this->setDocument($document);
        }

        if (isset($data->MerchantCategoryCode)) {
            $this->merchantCategoryCode = $data->MerchantCategoryCode;
        }

        if (isset($data->ContactName)) {
            $this->contactName = $data->ContactName;
        }

        if (isset($data->ContactPhone)) {
            $this->contactPhone = $data->ContactPhone;
        }

        if (isset($data->MailAddress)) {
            $this->mailAddress = $data->MailAddress;
        }

        if (isset($data->Website)) {
            $this->website = $data->Website;
        }

        if (isset($data->Blocked)) {
            $this->blocked = $data->Blocked;
        }

        if (isset($data->Analysis)) {
            $analysis = new Analysis();
            $analysis->populate($data->Analysis);
            $this->setAnalysis($analysis);
        }

        if (isset($data->BankAccount)) {
            $bank = new Bank();
            $bank->populate($data->BankAccount);
            $this->setBankAccount($bank);
        }

        if (isset($data->Address)) {
            $address = new Address();
            $address->populate($data->Address);
            $this->setAddress($address);
        }

        if (isset($data->Agreement)) {
            $agreement = new Agreement();
            $agreement->populate($data->Agreement);
            $this->setAgreement($agreement);
        }

        if (isset($data->Notification)) {
            $notification = new Notification();
            $notification->populate($data->Notification);
            $this->setNotification($notification);
        }

        if (isset($data->Attachments)) {
            foreach ($data->Attachments as $attachment) {
                $attachObj = new Attachment();
                $this->addAttachment($attachObj->populate($attachment));
            }
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
