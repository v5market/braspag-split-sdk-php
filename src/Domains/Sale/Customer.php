<?php

namespace Braspag\Split\Domains\Sale;

use DateTime;
use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Validation\Validator;
use Braspag\Split\Domains\Address;
use Braspag\Split\Domains\Subordinate\Document;

class Customer implements BraspagSplit
{
    private $email;
    private $name;
    private $identity;
    private $mobile;
    private $phone;
    private $deliveryAddress;
    private $address;
    private $birthdate;

    /**
     * Nome do comprador (No ambiente de Sandbox, o último nome
     * do comprador deverá ser ACCEPT. Ex.: “João da Silva Accept”)
     *
     * @param string $value
     *
     * @throws \InvalidArgumentException Quando o nome estiver vazio
     * @throws \LengthException Quando o nome tiver mais de 255 caracteres
     *
     * @return self
     */
    public function setName(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Customer name is required', 6000);
        }

        if (mb_strlen($value) > 255) {
            throw new \LengthException('Customer name must be less than 256 characters', 6001);
        }

        $this->name = $value;
        return $this;
    }

    /**
     * @return string Retorna o nome do cliente
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Email do comprador
     *
     * @param string $value
     */
    public function setEmail(string $value)
    {
        if (!Validator::email()->validator($value)) {
            throw new \InvalidArgumentException('Customer email is invalid', 6002);
        }

        $this->email = $value;
    }

    /**
     * @return string Retorna e-mail do comprador
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Documento do comprador (CPF ou CNPJ)
     *
     * @param Identity $value
     */
    public function setIdentity(Identity $value)
    {
        $this->identity = $value;
    }

    /**
     * @return Identity Retorna documento do comprador
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Telefone do comprador
     * (apenas números)
     *
     * @param string $value
     */
    public function setPhone(string $value)
    {
        $this->phone = preg_replace('/\D/', '', $value);
    }

    /**
     * @return string Retorna número de telefone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Celular do comprador
     *
     * @param string $value
     */
    public function setMobile(string $value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (!preg_match('/^(?:\+?\d{2,3})?(\d{2})9(\d+)$/', $value)) {
            throw new \InvalidArgumentException('Customer phone must have the DDD and the digit 9', 6003);
        }

        $this->mobile = $value;
    }

    /**
     * @return string Retorna número do celular do comprador
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Define endereço de entrega
     *
     * @param Address $value
     */
    public function setDeliveryAddress(Address $value)
    {
        $this->deliveryAddress = $value;
    }

    /**
     * @return Address Retorna endereço de entrega
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * Define endereço do comprador
     *
     * @param Address $value
     */
    public function setAddress(Address $value)
    {
        $this->address = $value;
    }

    /**
     * @return Address Retorna endereço do comprador
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Define data de nascimento
     *
     * @param DateTime $value
     */
    public function setBirthdate(DateTime $value)
    {
        $this->birthdate = $value;
    }

    /**
     * @return DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $result = [];

        foreach ($arr as $key => $value) {
            if ($value === null) {
                continue;
            }

            $key = ucfirst($key);

            if ($value instanceof Identity) {
                $result['IdentityType'] = $value->getType();
                $value = $value->getValue();
            } elseif ($value instanceof BraspagSplit) {
                $value = $value->toArray();
            } elseif ($value instanceof DateTime) {
                $value = $value->format('Y-m-d');
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->name = $data->Name ?? null;
        $this->email = $data->Email ?? null;
        $this->phone = $data->Phone ?? null;
        $this->mobile = $data->Mobile ?? null;

        if (isset($data->Identity) && mb_strlen($data->Identity) == 11) {
            $this->identity = Identity::cpf($data->Identity);
        } elseif (isset($data->Identity) && mb_strlen($data->Identity) == 14) {
            $this->identity = Identity::cnpj($data->Identity);
        }

        if (isset($data->Birthdate)) {
            $this->setBirthdate(DateTime::createFromFormat('Y-m-d', $data->Birthdate));
        }

        if (isset($data->Address)) {
            $address = new Address();
            $address->populate($data->Address);
            $this->setAddress($address);
        }

        if (isset($data->DeliveryAddress)) {
            $address = new Address();
            $address->populate($data->DeliveryAddress);
            $this->setDeliveryAddress($address);
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
