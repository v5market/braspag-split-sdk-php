<?php

namespace Braspag\Split\Traits;

trait SplitPayments
{
    private $subordinateMerchantId;
    private $amount;
    private $fares;
    private $splits = [];

    /**
     * MerchantId (Identificador) do Subordinado.
     *
     * @param string $value
     */
    public function setSubordinateMerchantId($value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Subordinate merchantid is required');
        }

        $this->subordinateMerchantId = $value;
    }

    /**
     * @return string
     */
    public function getSubordinateMerchantId()
    {
        return $this->subordinateMerchantId;
    }

    /**
     * Parte do valor total da transação referente a participação do Subordinado,
     * em centavos.
     *
     * @param integer $value
     */
    public function setAmount(int $value)
    {
        $this->amount = $value;
    }

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $mdr MDR(%) do Marketplace a ser descontado do valor referente a participação do Subordinado
     * @param integer $fee arifa Fixa(R$) a ser descontada do valor referente a participação do Subordinado,
     * em centavos.
     */
    public function setFares(float $mdr, int $fee)
    {
        $this->fares = [
            "Mdr" => $mdr,
            "Fee" => $fee
        ];
    }

    /**
     * @return array
     */
    public function getFares()
    {
        return $this->fares;
    }

    /**
     * @param string $merchantId MerchantId (Identificador) do Subordinado ou Marketplace.
     * @param string $amount Parte do valor calculado da transação a ser recebido
     *                       pelo Subordinado ou Marketplace, já descontando todas
     *                       as taxas (MDR e Tarifa Fixa)
     *
     * @throws \InvalidArgumentException Quando `$merchantId` ou `$amount` forem vazias
     */
    public function addSplit($merchantId, $amount)
    {
        if (empty($merchantId) || empty($amount)) {
            throw new \InvalidArgumentException('Merchant Id and And are required in Splits');
        }

        $this->splits[$merchantId] = [
            "MerchantId" => $merchantId,
            "Amount" => $amount,
        ];
    }

    /**
     * see self::addSplit
     * @param array $values
     * @throws \InvalidArgumentException Quando não houver os índices _MerchantId_ e _Amount_
     */
    public function setSplits(array $values)
    {
        foreach ($values as $value) {
            if (!isset($value["MerchantId"]) || !isset($value["Amount"])) {
                throw new \InvalidArgumentException('MerchantId and Amount indices are required');
            }

            $this->addSplit($value["MerchantId"], $value["Amount"]);
        }
    }

    /**
     * @return array
     */
    public function getSplits()
    {
        return $this->splits;
    }
}
