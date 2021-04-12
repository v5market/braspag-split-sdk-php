<?php

namespace Braspag\Split\Domains\Sale;

use DateTime;
use Braspag\Split\Interfaces\BraspagSplit;

class Refund implements BraspagSplit
{
    private $amount;
    private $status;
    private $receivedDate;

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
     * @param int $value
     */
    public function setStatus(int $value)
    {
        $this->status = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string|DateTime A _string_ deve estar no formato "AAAA-MM-DD H:i:s
     */
    public function setReceivedDate($value)
    {
        if ($value instanceof DateTime) {
            $date = $value->format('Y-m-d H:i:s');
        }

        $this->receivedDate = $value;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getReceivedDate()
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $this->receivedDate);
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
        $this->status = $data->Status ?? null;
        $this->receivedDate = $data->ReceivedDate ?? null;

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
