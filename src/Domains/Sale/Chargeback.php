<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Interfaces\BraspagSplit;

class Chargeback implements BraspagSplit
{
    private $amount;
    private $caseNumber;
    private $date;
    private $reasonCode;
    private $reasonMessage;
    private $status;
    private $rawData;

    /**
     * @param int $value
     */
    public function setAmount(int $value)
    {
        $this->amount = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Número do caso relacionado ao chargeback
     *
     * @param string $value
     */
    public function setCaseNumber(string $value)
    {
        $this->caseNumber = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaseNumber()
    {
        return $this->caseNumber;
    }

    /**
     * Data do chargeback
     *
     * @param string $value
     */
    public function setDate(string $value)
    {
        $this->date = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Código de motivo do chargeback
     * @see https://braspag.github.io//manual/braspag-pagador#lista-de-valores-payment.chargebacks[n].reasoncode-e-payment.chargebacks[n].reasonmessage
     *
     * @param string $value
     */
    public function setReasonCode(string $value)
    {
        $this->reasonCode = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * Mensagem de motivo do chargeback
     * https://braspag.github.io//manual/braspag-pagador#lista-de-valores-payment.chargebacks[n].reasoncode-e-payment.chargebacks[n].reasonmessage
     *
     * @param string $value
     */
    public function setReasonMessage(string $value)
    {
        $this->reasonMessage = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getReasonMessage()
    {
        return $this->reasonMessage;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));

        return array_combine($keys, array_values($arr));
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->amount = $data->Amount ?? null;
        $this->caseNumber = $data->CaseNumber ?? null;
        $this->date = $data->Date ?? null;
        $this->reasonCode = $data->ReasonCode ?? null;
        $this->reasonMessage = $data->ReasonMessage ?? null;
        $this->status = $data->Status ?? null;
        $this->rawData = $data->RawData ?? null;

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
