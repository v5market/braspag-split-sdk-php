<?php

namespace Braspag\Split\Domains\Subordinate;

use Braspag\Split\Constants\Card\Type as CardType;
use Braspag\Split\Constants\Card\Brand as CardBrand;

class MerchantDiscountRate
{
    private $cardType;
    private $cardBrand;
    private $initialInstallment = 1;
    private $finalInstallment = 12;
    private $percent = 0;

    /**
     * Define o tipo e bandeira do cartão
     *
     * @param string $type Veja o método setCardType
     * @param string $brand Veja o método setCardBrand
     */
    public function setCard(string $type, string $brand)
    {
        $this->setCardType($type);
        $this->setCardBrand($brand);
    }

    /**
     * Define o tipo de cartão
     *
     * @param string $value Veja Card::TYPE_*
     */
    public function setCardType(string $value)
    {
        if (!CardType::exist($value)) {
            throw new \OutOfBoundsException("Card type invalid. ' .
            'Use Braspag\Split\Constants\Card\Type", 13000);
        }

        $this->cardType = $value;
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Define o tipo de cartão
     *
     * @param string $value Veja Card::BRAND_*
     *
     * @throws OutOfBoundsException Ocorre quando a andeira do cartão é inválida
     */
    public function setCardBrand(string $value)
    {
        if (!CardBrand::exist($value)) {
            throw new \OutOfBoundsException("Card brand invalid. " .
            "Use Braspag\Split\Constants\Card\Brand", 13001);
        }

        $this->cardBrand = $value;
    }

    /**
     * @return string
     */
    public function getCardBrand()
    {
        return $this->cardBrand;
    }

    /**
     * Adiciona opções de parcelamento
     *
     * @param int $initial Parcela inicial
     * @param int $final Parcela final
     * @param float $percent Taxa/Juros
     */
    public function setInstallment(int $initial, int $final, float $percent)
    {
        $this->setInitialInstallment($initial);
        $this->setFinalInstallment($final);
        $this->setPercent($percent);
    }

    /**
     * Define a parcela inicial
     *
     * @param integer $value
     */
    public function setInitialInstallment(int $value)
    {
        if ($value > 12 || $value < 1) {
            throw new \OutOfRangeException('The value must be greater than 0 and less than 13', 13002);
        }

        $this->initialInstallment = $value;
    }

    /**
     * @return integer $value
     */
    public function getInitialInstallment()
    {
        return $this->initialInstallment;
    }

    /**
     * Define a parcela final
     *
     * @param integer $value
     */
    public function setFinalInstallment(int $value)
    {
        if ($value > 12 || $value < 1) {
            throw new \OutOfRangeException('The value must be greater than 0 and less than 13. Was: ' . $value, 13003);
        }

        $this->finalInstallment = $value;
    }

    /**
     * @return integer
     */
    public function getFinalInstallment()
    {
        return $this->finalInstallment;
    }

    /**
     * Define a porcentagem/juros da parcela
     *
     * @param float|int $value
     */
    public function setPercent($value)
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('The value must be of the float or integer type. ' .
            'Was: ' . gettype($value), 13004);
        }

        if (mb_strlen(substr(strrchr($value, "."), 1)) > 2) {
            throw new \LengthException('Value to two decimal places. Was: ' . $value, 13005);
        }

        $this->percent = floatval($value);
    }

    /**
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return [
            "PaymentArrangement" => [
                "Product" => $this->getCardType(),
                "Brand" => $this->getCardBrand()
            ],
            "InitialInstallmentNumber" => $this->getInitialInstallment(),
            "FinalInstallmentNumber" => $this->getFinalInstallment(),
            "Percent" => $this->getPercent()
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->PaymentArrangement)) {
            $this->setCardType($data->PaymentArrangement->Product);
            $this->setCardBrand($data->PaymentArrangement->Brand);
        }

        if (isset($data->InitialInstallmentNumber)) {
            $this->setInitialInstallment($data->InitialInstallmentNumber);
        }

        if (isset($data->FinalInstallmentNumber)) {
            $this->setFinalInstallment($data->FinalInstallmentNumber);
        }

        if (isset($data->Percent)) {
            $this->setPercent($data->Percent);
        }
    }
}
