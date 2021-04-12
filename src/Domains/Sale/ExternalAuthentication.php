<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Interfaces\BraspagSplit;

class ExternalAuthentication implements BraspagSplit
{
    private $returnUrl;
    private $cavv;
    private $xid;
    private $eci;
    private $version;
    private $referenceId;

    /**
     * @param string $value
     */
    public function setReturnUrl(string $value)
    {
        $this->returnUrl = $value;
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param string $value
     */
    public function setCavv(string $value)
    {
        $this->cavv = $value;
    }

    /**
     * @return string
     */
    public function getCavv()
    {
        return $this->cavv;
    }

    /**
     * @param string $value
     */
    public function setXid(string $value)
    {
        $this->xid = $value;
    }

    /**
     * @return string
     */
    public function getXid()
    {
        return $this->xid;
    }

    /**
     * @param string $value
     */
    public function setEci(string $value)
    {
        $this->eci = $value;
    }

    /**
     * @return string
     */
    public function getEci()
    {
        return $this->eci;
    }

    /**
     * @param int $value
     */
    public function setVersion(int $value)
    {
        $this->version = $value;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $value
     */
    public function setReferenceId(string $value)
    {
        $this->referenceId = $value;
    }

    /**
     * @return string
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->returnUrl = $value->ReturnUrl ?? null;
        $this->cavv = $value->Cavv ?? null;
        $this->xid = $value->Xid ?? null;
        $this->eci = $value->Eci ?? null;
        $this->version = $value->Version ?? null;
        $this->referenceId = $value->ReferenceId ?? null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = array_filter(get_object_vars($this));
        $keys = array_map('ucfirst', array_keys($arr));

        return array_combine($keys, array_values($arr));
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
