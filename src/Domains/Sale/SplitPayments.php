<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\SplitPayments as TraitSplitPayments;

class SplitPayments implements BraspagSplit
{
    use TraitSplitPayments;

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
            $data['Amount'] = $this->amount;
        }

        if ($this->fares) {
            $data['Fares'] = $this->fares;
        }

        if ($this->splits) {
            $data['Splits'] = array_values($this->splits);
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

        if (isset($data->Amount)) {
            $this->setAmount($data->Amount);
        }

        if (isset($data->Fares)) {
            $this->setFares($data->Fares->Mdr, $data->Fares->Fee);
        }

        if (isset($data->Splits)) {
            foreach ($data->Splits as $split) {
                $this->addSplit($split->MerchantId, $split->Amount);
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
