<?php

namespace Braspag\Split\Domains\FraudAnalysis;

use DateTime;
use Braspag\Split\Interfaces\BraspagSplit;

class FraudAlert implements BraspagSplit
{
    private $date;
    private $reasonMessage;
    private $incomingChargeback;

    /**
     * @param DateTime $value
     * @return self
     */
    public function setDate(DateTime $value)
    {
        $this->date = $value;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $value
     * @return self
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
     * @return self
     */
    public function setIncomingChargeback(string $value)
    {
        $this->incomingChargeback = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getIncominChargeback()
    {
        return $this->incomingChargeback;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return [
            'Date' => $this->date ? $this->date->format('Y-m-d H:i:s') : null,
            'ReasonMessage' => $this->reasonMessage,
            'IncomingChargeback' => $this->incomingChargeback
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->date = $data->Date ? DateTime::createFromFormat('Y-m-d H:i:s', $data->Date) : null;
        $this->reasonMessage = $data->ReasonMessage ?? null;
        $this->incomingChargeback = $data->IncomingChargeback ?? null;

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
