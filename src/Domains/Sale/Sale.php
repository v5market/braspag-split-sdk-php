<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Request\Response;

class Sale implements BraspagSplit
{
    use Response;

    private $merchantOrderId;
    private $customer;
    private $merchant;
    private $payment;

    /**
     * Instancia uma nova classe responsável pela requisição
     * de vendas
     *
     * @param string|null $merchantOrderId
     */
    public function __construct(string $merchantOrderId = null)
    {
        if ($merchantOrderId) {
            $this->setMerchantOrderId($merchantOrderId);
        }

        $this->merchant = new \stdClass();
    }

    /**
     * Define o número do pedido da loja
     *
     * @param string $value
     *
     * @throws \InvalidArgumentException Quando o número de caracteres for maior que o permitido
     */
    public function setMerchantOrderId(string $value)
    {
        if (mb_strlen($value) > 50) {
            throw new \LengthException('Merchant Order ID must be less than 51 characters', 8000);
        }

        $this->merchantOrderId = $value;
    }

    /**
     * @return string
     */
    public function getMerchantOrderId()
    {
        return $this->merchantOrderId;
    }

    /**
     * Define os dados dos clientes
     *
     * @param Customer $value;
     */
    public function setCustomer(Customer $value)
    {
        $this->customer = $value;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Define os dados dos clientes
     *
     * @param Payment $value
     */
    public function setPayment(Payment $value)
    {
        $this->payment = $value;
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Retorna o Merchant Master ID
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchant->Id;
    }

    /**
     * Retorna o Merchant Master Name
     */
    public function getMerchantName()
    {
        return $this->merchant->TradeName;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $required = [
            'merchantOrderId' => 'sale merchant order id',
            'customer' => 'Customer data',
            'payment' => 'Payment data'
        ];

        foreach ($required as $key => $value) {
            if (empty($this->{$key})) {
                throw new \InvalidArgumentException("$value is required", 8001);
            }
        }

        $data = [
            'MerchantOrderId' => $this->merchantOrderId,
            'Customer' => $this->customer->toArray(),
            'Payment' => $this->payment->toArray()
        ];

        if ($this->merchant && isset($this->merchant->Id) && isset($this->merchant->TradeName)) {
            $data["Merchant"] = [
                "Id" => $this->merchant->Id,
                "TradeName" => $this->merchant->TradeName
            ];
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->MerchantOrderId)) {
            $this->merchantOrderId = $data->MerchantOrderId;
        }

        if (isset($data->Customer)) {
            $customer = new Customer();
            $customer->populate($data->Customer);
            $this->customer = $customer;
        }

        if (isset($data->Payment)) {
            $payment = new Payment();
            $payment->populate($data->Payment);
            $this->payment = $payment;
        }

        if (isset($data->Merchant)) {
            $merchant = new \stdClass();
            $merchant->Id = $data->Merchant->Id;
            $merchant->TradeName = $data->Merchant->TradeName;
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
