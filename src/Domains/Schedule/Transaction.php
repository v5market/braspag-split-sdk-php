<?php

namespace Braspag\Split\Domains\Schedule;

use Braspag\Split\Interfaces\BraspagSplit;

class Transaction implements BraspagSplit
{
    private $paymentId;
    private $capturedDate;
    private $schedules = [];
    private $merchantId;
    private $nsu;
    private $authorizationCode;
    private $authorizationDate;
    private $status;
    private $statusDescription;
    private $cardNumber;
    private $orderId;

    /**
     * @return string Identificador da transação.
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @return string Data de captura da transação.
     */
    public function getCapturedDate()
    {
        return $this->capturedDate;
    }

    /**
     * @return Schedule[]
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return string|int|float
     */
    public function getNsu()
    {
        return $this->nsu;
    }

    /**
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @return string
     */
    public function getAuthorizationDate()
    {
        return $this->authorizationDate;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getStatusDescription()
    {
        return $this->statusDescription;
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $arr['schedules'] = array_map([$this, 'convertScheduleToArray'], $this->schedules);

        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);

        return array_filter(array_combine($keys, $values), function ($item) {
            return $item !== null;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->paymentId = $data->PaymentId;
        $this->capturedDate = $data->CapturedDate;
        $this->merchantId = $data->MerchantId ?? null;
        $this->nsu = $data->Nsu ?? null;
        $this->authorizationCode = $data->AuthorizationCode ?? null;
        $this->authorizationDate = $data->AuthorizationDate ?? null;
        $this->status = $data->Status ?? null;
        $this->statusDescription = $data->StatusDescription ?? null;
        $this->cardNumber = $data->CardNumber ?? null;
        $this->orderId = $data->OrderId ?? null;

        foreach ($data->Schedules as $schedule) {
            $newSchedule = new Schedule();
            $newSchedule->populate($schedule);
            $this->schedules[] = $newSchedule;
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

    /**
     * @param Schedule[] $schedule
     */
    private function convertScheduleToArray($schedule)
    {
        return $schedule->toArray();
    }
}
