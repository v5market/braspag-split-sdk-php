<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Request\Response;
use Braspag\Split\Traits\Sale\{
  ProviderReturn,
  ReasonReturn
};

class Capture implements BraspagSplit
{
    use ProviderReturn;
    use ReasonReturn;
    use Response;

    private $status;
    private $reasonCode;
    private $reasonMessage;
    private $returnCode;
    private $returnMessage;
    private $splitPayments = [];

    /**
     * Status da transação no Pagador
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-21-payment.status
     *
     * @param string $value
     */
    public function setStatus(string $value)
    {
        $this->status = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
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
     * @param string $value
     */
    public function setReturnCode(string $value)
    {
        $this->returnCode = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnCode()
    {
        return $this->returnCode;
    }

    /**
     * @param string $value
     */
    public function setReturnMessage(string $value)
    {
        $this->returnMessage = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnMessage()
    {
        return $this->returnMessage;
    }

    /**
     * @param SplitPayments[] $value
     */
    public function setSplitPayments(array $values)
    {
        $this->splitPayments = [];

        foreach ($values as $value) {
            $this->addSplitPayments($value);
        }
    }

    /**
     * @param SplitPayments $value
     */
    public function addSplitPayments(SplitPayments $value)
    {
        $this->splitPayments[] = $value;
        return $this;
    }

    /**
     * @return SplitPayments[]
     */
    public function getSplitPayments()
    {
        return $this->splitPayments;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $splitPayments = [];

        foreach ($this->splitPayments as $value) {
            $splitPayments[] = $value->toArray();
        }

        return [
            'Status' => $this->status,
            'ReasonCode' => $this->reasonCode,
            'ReasonMessage' => $this->reasonMessage,
            'ReturnCode' => $this->returnCode,
            'ReturnMessage' => $this->returnMessage,
            'SplitPayments' => $splitPayments,
            'ProviderReturnCode' => $this->providerReturnCode,
            'ProviderReturnMessage' => $this->providerReturnMessage,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->status = $data->Status ?? null;
        $this->reasonCode = $data->ReasonCode ?? null;
        $this->reasonMessage = $data->ReasonMessage ?? null;
        $this->providerReturnCode = $data->ProviderReturnCode ?? null;
        $this->providerReturnMessage = $data->ProviderReturnMessage ?? null;
        $this->returnCode = $data->ReturnCode ?? null;
        $this->returnMessage = $data->ReturnMessage ?? null;

        if (isset($data->SplitPayments)) {
            foreach ($data->SplitPayments as $value) {
                $splitPayments = new SplitPayments();
                $splitPayments->populate($value);
                $this->addSplitPayments($splitPayments);
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
