<?php

namespace Braspag\Split\Domains\Chargeback;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Request\Response;

class Chargeback implements BraspagSplit
{
    use Response;

    private $chargebackSplitPayments = [];

    /**
     * @return array
     */
    public function getChargebackSplitPayments()
    {
        return $this->chargebackSplitPayments;
    }

    /**
     * @param ChargebackSplitPayments $value
     */
    private function addChargebackSplitPayment(ChargebackSplitPayments $value)
    {
        $this->chargebackSplitPayments[] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return [
            "ChargebackSplitPayments" => $this->chargebackSplitPayments
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        foreach ($data->ChargebackSplitPayments as $payment) {
            $split = new ChargebackSplitPayments();
            $split->populate($payment);
            $this->addChargebackSplitPayment($split);
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
