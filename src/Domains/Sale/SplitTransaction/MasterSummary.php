<?php

namespace Braspag\Split\Domains\Sale\SplitTransaction;

use Braspag\Split\Interfaces\BraspagSplit;

class MasterSummary implements BraspagSplit
{
    /** @var array (Apenas Leitura) */
    private $commission;

    /** @var int (Apenas Leitura) */
    private $totalGrossAmount;

    /** @var int (Apenas Leitura) */
    private $totalNetAmount;

    /**
     * @return array
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * @return string|null
     */
    public function getCommissionSplitId()
    {
        return $this->commission['SplitId'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getCommissionNetAmount()
    {
        return $this->commission['NetAmount'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getCommissionGrossAmount()
    {
        return $this->commission['GrossAmount'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getTotalGrossAmount()
    {
        return $this->totalGrossAmount;
    }

    /**
     * @return int|null
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

        return array_filter(array_combine($keys, $values), function ($item) {
            return $item !== null;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->commission = isset($data->Commission) ? (array)$data->Commission : null;
        $this->totalGrossAmount = $data->TotalGrossAmount ?? null;
        $this->totalNetAmount = $data->TotalNetAmount ?? null;

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
