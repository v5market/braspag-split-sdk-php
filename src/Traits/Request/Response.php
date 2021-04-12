<?php

namespace Braspag\Split\Traits\Request;

trait Response
{
    /** @var string armazena retorno bruto da API */
    private $responseRaw;

    /**
     * Define o retorno da API
     *
     * @param string $value json
     *
     * @return self
     */
    public function setResponseRaw(string $value)
    {
        $this->responseRaw = $value;

        return $this;
    }

    /**
     * Retorna o valor bruto capturado pela requisição da API
     *
     * @return string|null
     */
    public function getResponseRaw(): ?string
    {
        return $this->responseRaw;
    }
}
