<?php

namespace Braspag\Split\Domains\FraudAnalysis;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Validation\Validator;

class Browser implements BraspagSplit
{
    private $hostName;
    private $cookiesAccepted;
    private $email;
    private $type;
    private $ipAddress;

    /**
     * Nome do host informado pelo browser do comprador e identificado através do cabeçalho HTTP
     *
     * @param string $value
     */
    public function setHostname(string $value)
    {
        $this->hostName = $value;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostName;
    }

    /**
     * Identifica se o browser do comprador aceita cookies
     *
     * @param mixed $value Se o valor não for booleano, converte-o
     */
    public function setCookiesAccepted($value)
    {
        $this->cookiesAccepted = !!$value;
    }

    /**
     * @return boolean
     */
    public function getCookiesAccepted()
    {
        return $this->cookiesAccepted;
    }

    /**
     * E-mail registrado no browser do comprador.
     *
     * @param string $value
     */
    public function setEmail(string $value)
    {
        if (!Validator::email()->validator($value)) {
            throw new \InvalidArgumentException('Email is invalid', 4000);
        }

        $this->email = $value;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Nome do browser utilizado pelo comprador e identificado através do cabeçalho HTTP
     *
     * @param string $value
     */
    public function setType(string $value)
    {
        $this->type = $value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Endereço de IP do comprador. Formato IPv4 ou IPv6
     *
     * @param string $value
     */
    public function setIpAddress(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException('Ip address is invalid', 4001);
        }

        $this->ipAddress = $value;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);

        return array_combine($keys, $values);
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->cookiesAccepted = $data->CookiesAccepted ?? null;
        $this->email = $data->Email ?? null;
        $this->hostName = $data->HostName ?? null;
        $this->ipAddress = $data->IpAddress ?? null;
        $this->type = $data->Type ?? null;

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
