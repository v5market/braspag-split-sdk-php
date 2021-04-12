<?php

namespace Braspag\Split\Domains\PaymentMethod;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Constants\Card\Brand as Constants;

abstract class AbstractCard implements BraspagSplit
{
    protected $cardNumber;
    protected $holder;
    protected $expirationDate;
    protected $securityCode;
    protected $brand;
    protected $saveCard;
    protected $alias;

    /**
     * Número do cartão de crédito
     *
     * @param string $value
     */
    public function setCardNumber(string $value)
    {
        $this->cardNumber = preg_replace('/\D/', '', $value);
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Nome do portador impresso no cartão de crédito
     *
     * @param string $value
     */
    public function setHolder(string $value)
    {
        $this->holder = $value;
    }

    /**
     * @return string
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * Data de validade do cartão de crédito
     *
     * @param string $value MM/AAAA
     */
    public function setExpirationDate(string $value)
    {
        if (!preg_match('/^(?:0[1-9]|1[0-2])\/\d{4}$/', $value)) {
            throw new \InvalidArgumentException('Expiration date must be in MM/YYYY format', 9001);
        }

        $this->expirationDate = $value;
    }

    /**
     * @return string
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Código de segurança no verso do cartão de crédito
     *
     * @param string $value
     */
    public function setSecurityCode(string $value)
    {
        $this->securityCode = $value;
    }

    /**
     * @return string
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * Bandeira do cartão de crédito
     *
     * @param string $value Veja Constants::BRANDS
     */
    public function setBrand(string $value)
    {
        $this->brand = $value;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Indica se os dados do cartão de crédito serão armazenados no Cartão Protegido
     *
     * @param mixed $value Se o valor não for booleano, converte-o
     */
    public function setSaveCard($value)
    {
        if ($value == 'false') {
            $value = false; //bugfix
        }

        $this->saveCard = !!$value;
    }

    /**
     * @return boolean
     */
    public function getSaveCard()
    {
        return $this->saveCard;
    }

    /**
     * Alias (apelido) do cartão de crédito salvo no Cartão Protegido
     *
     * @param string $value
     */
    public function setAlias(string $value)
    {
        if (mb_strlen($value) > 64) {
            throw new \LengthException('Address number must be less than 65 characters', 9002);
        }

        $this->alias = $value;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);
        $arr = array_combine($keys, $values);

        return array_filter($arr, function ($item) {
            return $item !== null;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->cardNumber = $data->CardNumber ?? null;
        $this->holder = $data->Holder ?? null;
        $this->expirationDate = $data->ExpirationDate ?? null;
        $this->securityCode = $data->SecurityCode ?? null;
        $this->brand = $data->Brand ?? null;
        $this->saveCard = $data->SaveCard ?? null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return boolean Retorna se é um cartão de crédito
     */
    abstract public function isCreditCard(): bool;
}
