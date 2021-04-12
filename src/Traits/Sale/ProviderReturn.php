<?php

namespace Braspag\Split\Traits\Sale;

trait ProviderReturn
{
    private $providerReturnCode;
    private $providerReturnMessage;

    /**
     * @param string $value
     */
    public function setProviderReturnCode($value)
    {
        $this->providerReturnCode = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getProviderReturnCode()
    {
        return $this->providerReturnCode;
    }

    /**
     * @param string $value
     */
    public function setProviderReturnMessage($value)
    {
        $this->providerReturnMessage = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getProviderReturnMessage()
    {
        return $this->providerReturnMessage;
    }
}
