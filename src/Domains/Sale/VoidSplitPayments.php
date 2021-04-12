<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\SplitPayments;

class VoidSplitPayments implements BraspagSplit
{
    use SplitPayments {
        SplitPayments::setAmount as setVoidedAmount;
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
            $data['VoidedAmount'] = $this->amount;
        }

        if ($this->splits) {
            $data['VoidedSplits'] = array_map(function ($arr) {
                return [
                "VoidedAmount" => $arr['Amount'],
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

        if (isset($data->VoidedAmount)) {
            $this->setVoidedAmount($data->VoidedAmount);
        }

        if (isset($data->VoidedSplits)) {
            foreach ($data->VoidedSplits as $split) {
                $this->addSplit($split->MerchantId, $split->VoidedAmount);
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
