<?php

namespace Braspag\Split\Traits\Sale;

trait ReasonReturn
{
    private $reasonCode;
    private $reasonMessage;

    /**
     * Código de retorno da operação
     *
     * @return string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * Mensagem de retorno da operação
     *
     * @return string
     */
    public function getReasonMessage()
    {
        return $this->reasonMessage;
    }
}
