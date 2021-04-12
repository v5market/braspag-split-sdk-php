<?php

namespace Braspag\Split\Domains\Sale\SplitTransaction;

use Braspag\Split\Interfaces\BraspagSplit;

class Split implements BraspagSplit
{
    /** @var string (Apenas leitura) */
    private $id;

    /** @var int (Apenas leitura) */
    private $netAmount;

    /** @var int (Apenas leitura) */
    private $grossAmount;

    /** @var array (Apenas leitura) */
    private $fares;

    /** @var Merchant (Apenas leitura) */
    private $merchant;

    /** @var boolean (Apenas leitura) */
    private $payoutBlocked;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNetAmount()
    {
        return $this->netAmount;
    }

    /**
     * @return int
     */
    public function getGrossAmount()
    {
        return $this->grossAmount;
    }

    /**
     * @return array
     */
    public function getFares()
    {
        return $this->fares;
    }

    /**
     * @return int|null
     */
    public function getFaresMdr()
    {
        return $this->fares['Mdr'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getFaresFee()
    {
        return $this->fares['Fee'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getFaresDiscountedMdrAmount()
    {
        return $this->fares['DiscountedMdrAmount'] ?? null;
    }

    /**
     * @return object|null
     */
    public function getFaresCustomPayoutFares()
    {
        return $this->fares['CustomPayoutFares'] ?? null;
    }

    /**
     * @return boolean
     */
    public function getPayoutBlocked()
    {
        return $this->payoutBlocked;
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
        $this->id = $data->Id;
        $this->netAmount = $data->NetAmount;
        $this->grossAmount = $data->GrossAmount;
        $this->fares = isset($data->Fares) ? (array)$data->Fares : [];
        $this->payoutBlocked = $data->PayoutBlocked;

        if (isset($data->Merchant)) {
            $merchant = new Merchant();
            $merchant->populate($data->Merchant);
            $this->merchant = $merchant;
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
