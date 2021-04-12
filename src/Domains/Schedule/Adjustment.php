<?php

namespace Braspag\Split\Domains\Schedule;

use DateTime;
use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Request\Response;

class Adjustment implements BraspagSplit
{
    use Response;

    private $merchantIdToDebit;
    private $merchantIdToCredit;
    private $forecastedDate;
    private $amount;
    private $description;
    private $transactionId;
    private $id;
    private $createdAt;
    private $createdBy;
    private $status;

    /**
     * @return string
     */
    public function getMerchantIdToDebit()
    {
        return $this->merchantIdToDebit;
    }

    /**
     * @param string $value
     *
     * @return  self
     */
    public function setMerchantIdToDebit(string $value)
    {
        $this->merchantIdToDebit = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantIdToCredit()
    {
        return $this->merchantIdToCredit;
    }

    /**
     * @param string $value
     *
     * @return  self
     */
    public function setMerchantIdToCredit(string $value)
    {
        $this->merchantIdToCredit = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getForecastedDate()
    {
        return DateTime::createFromFormat('Y-m-d', $this->forecastedDate);
    }

    /**
     * @param string $value
     *
     * @return  self
     */
    public function setForecastedDate(string $value)
    {
        $this->forecastedDate = $value;

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
     * @param int $value
     *
     * @return  self
     */
    public function setAmount(int $value)
    {
        $this->amount = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $value
     *
     * @return  self
     */
    public function setDescription(string $value)
    {
        $this->description = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $value
     *
     * @return  self
     */
    public function setTransactionId(string $value)
    {
        $this->transactionId = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $this->createdAt);
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->id = $data->id ?? null;
        $this->merchantIdToDebit = $data->merchantIdToDebit ?? null;
        $this->merchantIdToCredit = $data->merchantIdToCredit ?? null;
        $this->forecastedDate = $data->forecastedDate ?? null;
        $this->amount = $data->amount ?? null;
        $this->description = $data->description ?? null;
        $this->transactionId = $data->transactionId ?? null;
        $this->status = $data->status ?? null;
        $this->createdAt = $data->createdAt ?? null;
        $this->createdBy = $data->createdBy ?? null;

        return $this;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
