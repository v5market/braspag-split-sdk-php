<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Domains\Sale\SplitTransaction\Merchant;
use Braspag\Split\Domains\Sale\SplitTransaction\Split;
use Braspag\Split\Domains\Sale\SplitTransaction\MasterSummary;
use Braspag\Split\Domains\Sale\SplitTransaction\TransactionFares;
use Braspag\Split\Interfaces\BraspagSplit;

class SplitTransaction implements BraspagSplit
{
    /** @var string ID do split de transação (Apenas leitura) */
    private $id;

    /** @var Merchant (Apenas leitura) */
    private $merchant;

    /** @var int (Apenas leitura) */
    private $masterRateDiscountTypeId;

    /** @var string (Apenas leitura) */
    private $masterRateDiscountType;

    /** @var boolean (Apenas leitura) */
    private $releasedToAnticipation;

    /** @var Split[] (Apenas leitura) */
    private $splits = [];

    /** @var MasterSummary (Apenas leitura) */
    private $masterSummary;

    /** @var TransactionFares (Apenas leitura) */
    private $transactionFares;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Merchant
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * @return int
     */
    public function getMasterRateDiscountTypeId()
    {
        return $this->masterRateDiscountTypeId;
    }

    /**
     * Opções de Configuração da Transação
     *
     * @param string $value Commission = O desconto será feito sobre o valor de comissão que o Master tem a receber na
     *                      transação.
     *                      Sale = O desconto será feito sobre o valor de venda que o Master tem a receber na transação.
     */
    public function setMasterRateDiscountType(string $value)
    {
        $this->masterRateDiscountType = $value;
    }

    /**
     * @return string
     */
    public function getMasterRateDiscountType()
    {
        return $this->masterRateDiscountType;
    }

    /**
     * @return boolean
     */
    public function getReleasedToAnticipation()
    {
        return $this->releasedToAnticipation;
    }

    /**
     * @return Split[]
     */
    public function getSplits()
    {
        return $this->splits;
    }

    /**
     * @return MasterSummary
     */
    public function getMasterSummary()
    {
        return $this->masterSummary;
    }

    /**
     * @return TransactionFares
     */
    public function getTransactionFares()
    {
        return $this->transactionFares;
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
        $this->id = $data->Id ?? null;
        $this->masterRateDiscountTypeId = $data->MasterRateDiscountTypeId ?? null;
        $this->masterRateDiscountType = $data->MasterRateDiscountType ?? null;
        $this->releasedToAnticipation = $data->ReleasedToAnticipation ?? null;

        if (isset($data->Merchant)) {
            $merchant = new Merchant();
            $merchant->populate($data->Merchant);
            $this->merchant = $merchant;
        }

        if (isset($data->Splits)) {
            foreach ($data->Splits as $value) {
                $split = new Split();
                $split->populate($value);
                $this->splits[] = $split;
            }
        }

        if (isset($data->MasterSummary)) {
            $masterSummary = new MasterSummary();
            $masterSummary->populate($data->MasterSummary);
            $this->masterSummary = $masterSummary;
        }

        if (isset($data->TransactionFares)) {
            $transactionFares = new TransactionFares();
            $transactionFares->populate($data->TransactionFares);
            $this->transactionFares = $transactionFares;
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
