<?php

namespace Braspag\Split\Domains\Chargeback;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\SplitPayments;

class ChargebackSplitPayments implements BraspagSplit
{
    use SplitPayments {
        SplitPayments::setAmount as setChargebackAmount;
        SplitPayments::getAmount as getChargebackAmount;
    }

    /**
     * @return array
     */
    public function getSplits()
    {
        return $this->splits;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $data = [];

        if ($this->subordinateMerchantId) {
            $data['SubordinateMerchantId'] = $this->subordinateMerchantId;
        }

        if ($this->amount) {
            $data['ChargebackAmount'] = $this->amount;
        }

        if ($this->splits) {
            $data['ChargebackSplits'] = array_map(function ($arr) {
                return [
                "ChargebackAmount" => $arr['Amount'],
                "MerchantId" => $arr['MerchantId'],
                ];
            }, array_values($this->splits));
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->SubordinateMerchantId)) {
            $this->setSubordinateMerchantId($data->SubordinateMerchantId);
        }

        if (isset($data->ChargebackAmount)) {
            $this->setChargebackAmount($data->ChargebackAmount);
        }

        if (isset($data->ChargebackSplits)) {
            foreach ($data->ChargebackSplits as $split) {
                $this->addSplit($split->MerchantId, $split->ChargebackAmount);
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
