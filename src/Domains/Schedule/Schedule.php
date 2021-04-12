<?php

namespace Braspag\Split\Domains\Schedule;

use DateTime;
use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Request\Response;

class Schedule implements BraspagSplit
{
    use Response;

    private $id;
    private $paymentId;
    private $merchantId;
    private $paymentDate;
    private $forecastedDate;
    private $installments;
    private $installmentAmount;
    private $installmentNumber;
    private $event;
    private $eventDescription;
    private $eventStatus;
    private $sourceId;
    private $mdr;
    private $commission;
    private $installmentNetAmount;
    private $installmentGrossAmount;
    private $settlementLocked;


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
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * @return DateTime
     */
    public function getForecastedDate()
    {
        return $this->forecastedDate;
    }

    /**
     * @return integer
     */
    public function getInstallments()
    {
        return $this->installments;
    }

    /**
     * @return integer
     */
    public function getInstallmentAmount()
    {
        return $this->installmentAmount;
    }

    /**
     * @return integer
     */
    public function getInstallmentNumber()
    {
        return $this->installmentNumber;
    }

    /**
     * @return integer
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getEventDescription()
    {
        return $this->eventDescription;
    }

    /**
     * @return string
     */
    public function getEventStatus()
    {
        return $this->eventStatus;
    }

    /**
     * @return string
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @return integer
     */
    public function getMdr()
    {
        return $this->mdr;
    }

    /**
     * @return boolean
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * @return int|float
     */
    public function getInstallmentNetAmount()
    {
        return $this->installmentNetAmount;
    }

    /**
     * @return int|float
     */
    public function getInstallmentGrossAmount()
    {
        return $this->installmentGrossAmount;
    }

    /**
     * @return boolean
     */
    public function getSettlementLocked()
    {
        return $this->settlementLocked;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $result = [];

        foreach ($arr as $key => $value) {
            if ($value === null) {
                continue;
            }

            $key = ucfirst($key);

            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d');
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->id = $data->Id ?? null;
        $this->paymentId = $data->PaymentId ?? null;
        $this->merchantId = $data->MerchantId ?? null;
        $this->installments = $data->Installments ?? null;
        $this->installmentAmount = $data->InstallmentAmount ?? null;
        $this->installmentNumber = $data->InstallmentNumber ?? null;
        $this->event = $data->Event ?? null;
        $this->eventDescription = $data->EventDescription ?? null;
        $this->eventStatus = $data->EventStatus ?? null;
        $this->sourceId = $data->SourceId ?? null;
        $this->mdr = $data->Mdr ?? null;
        $this->commission = $data->Commission ?? null;
        $this->installmentNetAmount = $data->InstallmentNetAmount ?? null;
        $this->installmentGrossAmount = $data->InstallmentGrossAmount ?? null;
        $this->settlementLocked = $data->SettlementLocked ?? null;

        if (isset($data->PaymentDate)) {
            $this->paymentDate = \DateTime::createFromFormat('Y-m-d', $data->PaymentDate);
        }

        if (isset($data->ForecastedDate)) {
            $this->forecastedDate = DateTime::createFromFormat('Y-m-d', $data->ForecastedDate);
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
