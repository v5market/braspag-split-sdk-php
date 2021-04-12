<?php

namespace Braspag\Split\Domains\Sale\SplitTransaction;

use Braspag\Split\Interfaces\BraspagSplit;

class TransactionFares implements BraspagSplit
{
    /** @var int (Apenas Leitura) */
    private $discountedAmount;

    /** @var int (Apenas Leitura) */
    private $appliedMdr;

    /** @var int (Apenas Leitura) */
    private $fee;

    /** @var int (Apenas Leitura) */
    private $totalNetAmount;

    /**
     * @return int
     */
    public function getDiscountedAmount()
    {
        return $this->discountedAmount;
    }

    /**
     * @return int
     */
    public function getAppliedMdr()
    {
        return $this->appliedMdr;
    }

    /**
     * @return int
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @return int
     */
    public function getTotalNetAmount()
    {
        return $this->totalNetAmount;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);
        $result = array_combine($keys, $values);

        return array_filter($result, function ($item) {
            return $item !== null;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->discountedAmount = $data->DiscountedAmount ?? null;
        $this->appliedMdr = $data->AppliedMdr ?? null;
        $this->fee = $data->Fee ?? null;
        $this->totalNetAmount = $data->totalNetAmount ?? null;

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
