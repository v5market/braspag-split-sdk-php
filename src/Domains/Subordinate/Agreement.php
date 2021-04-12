<?php

declare(strict_types=1);

namespace Braspag\Split\Domains\Subordinate;

use Braspag\Split\Interfaces\BraspagSplit;

class Agreement implements BraspagSplit
{
    private $fee;
    private $antiFraudFee;
    private $antiFraudFeeWithReview;
    private $merchantDiscountRateId = null;
    private $merchantDiscountRates = [];
    private $mdrPercentage = null;

    /**
     * Taxa fixa por transação. Valor em centavos.
     *
     * @param integer $value
     */
    public function setFee(int $value)
    {
        $this->fee = $value;
    }

    /**
     * @return integer
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @param mixed $value
     */
    public function setAntiFraudFee($value)
    {
        $this->antiFraudFee = $value;
    }

    /**
     * @return mixed
     */
    public function getAntiFraudFee()
    {
        return $this->antiFraudFee;
    }

    /**
     * @param mixed $value
     */
    public function setAntiFraudFeeWithReview($value)
    {
        $this->antiFraudFeeWithReview = $value;
    }

    /**
     * @return mixed
     */
    public function getAntiFraudFeeWithReview()
    {
        return $this->antiFraudFeeWithReview;
    }

    /**
     * @param string $value
     */
    public function setMerchantDiscountRateId(string $value)
    {
        $this->merchantDiscountRateId = $value;
    }

    /**
     * @return string
     */
    public function getMerchantDiscountRateId()
    {
        return $this->merchantDiscountRateId;
    }

    /**
     * Define as taxas de juros
     *
     * @param MerchantDiscountRate[] $values
     */
    public function setMerchantDiscountRates($values)
    {
        if (!is_array($values)) {
            throw new \InvalidArgumentException('The value must be an array of MerchantDiscountRate', 9003);
        }

        foreach ($values as $value) {
            if (!($value instanceof MerchantDiscountRate)) {
                throw new \InvalidArgumentException('The object must be an instance of MerchantDiscountRate', 9004);
            }
        }

        $this->merchantDiscountRates = $values;
    }

    /**
     * Adiciona uma taxa de juros
     *
     * @param MerchantDiscountRate $value
     */
    public function addMerchantDiscountRate(MerchantDiscountRate $value)
    {
        $this->merchantDiscountRates[] = $value;
    }

    /**
     * Porcentagem da taxa de desconto única que será aplicada para todos
     * os acordos entre Master e Subordinado.
     * Valor com até duas casas decimais
     *
     * @param float $value
     */
    public function setMdrPercentage(float $value)
    {
        $this->mdrPercentage = $value;
    }

    /**
     * @return float
     */
    public function getMdrPercentage()
    {
        return $this->mdrPercentage;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $result = [
            'Fee' => $this->fee,
            'AntiFraudFee' => $this->antiFraudFee,
            'AntiFraudFeeWithReview' => $this->antiFraudFeeWithReview,
            'MerchantDiscountRateId' => $this->merchantDiscountRateId
        ];

        if ($this->mdrPercentage) {
            $result['MdrPercentage'] = $this->mdrPercentage;
        } else {
            $result['MerchantDiscountRates'] = [];

            foreach ($this->merchantDiscountRates as $mdr) {
                $result['MerchantDiscountRates'][] = $mdr->toArray();
            }
        }

        return array_filter($result);
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->Fee)) {
            $this->setFee($data->Fee);
        }

        if (isset($data->AntiFraudFee)) {
            $this->setAntiFraudFee($data->AntiFraudFee);
        }

        if (isset($data->AntiFraudFeeWithReview)) {
            $this->setAntiFraudFeeWithReview($data->AntiFraudFeeWithReview);
        }

        if (isset($data->MerchantDiscountRates)) {
            foreach ($data->MerchantDiscountRates as $merchantDiscountRate) {
                $instance = new MerchantDiscountRate();
                $instance->populate($merchantDiscountRate);

                $this->addMerchantDiscountRate($instance);
            }
        }

        if (isset($data->MdrPercentage)) {
            $this->setMdrPercentage($data->MdrPercentage);
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
