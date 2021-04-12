<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Interfaces\BraspagSplit;

/**
 * Classe usada no retorno da transição de vendas
 */
class Credentials implements BraspagSplit
{
    private $code;
    private $key;
    private $username;
    private $password;
    private $signature;

    /**
     * Afiliação gerada pela adquirente
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Chave de afiliação/token gerado pela adquirente
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Usuário gerado no credenciamento com a adquirente Getnet
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Senha gerada no credenciamento com a adquirente Getnet
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * ID do terminal no credenciamento com a adquirente Global Payments
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));

        return array_filter(array_combine($keys, array_values($arr)));
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->code = $data->Code ?? null;
        $this->key = $data->Key ?? null;
        $this->username = $data->Username ?? null;
        $this->password = $data->Password ?? null;
        $this->signature = $data->Signature ?? null;

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
