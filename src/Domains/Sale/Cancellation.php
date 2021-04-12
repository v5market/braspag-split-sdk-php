<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Sale\{
  ProviderReturn,
  ReasonReturn
};

class Cancellation implements BraspagSplit
{
    use ProviderReturn;
    use ReasonReturn;

    private $status;
    private $reasonCode;
    private $reasonMessage;
    private $returnCode;
    private $returnMessage;
    private $voidSplitPayments = [];

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @return string
     */
    public function getReasonMessage()
    {
        return $this->reasonMessage;
    }

    /**
     * @return string
     */
    public function getReturnCode()
    {
        return $this->returnCode;
    }

    /**
     * @return string
     */
    public function getReturnMessage()
    {
        return $this->returnMessage;
    }

    /**
     * @return VoidSplitPayments[]
     */
    public function getVoidSplitPayments()
    {
        return $this->voidSplitPayments;
    }

    /**
     * @param VoidSplitPayments $value
     */
    private function addVoidSplitPayments(VoidSplitPayments $value)
    {
        $this->voidSplitPayments[] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $data = get_object_vars($this);

        foreach ($this->voidSplitPayments as $key => $value) {
            $data["VoidSplitPayments"][$key] = $value->toArray();
        }

        $keys = array_map('ucfirst', array_keys($data));

        return array_combine($keys, array_values($data));
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->status = $data->Status ?? null;
        $this->reasonCode = $data->ReasonCode ?? null;
        $this->reasonMessage = $data->ReasonMessage ?? null;
        $this->providerReturnCode = $data->ProviderReturnCode ?? null;
        $this->providerReturnMessage = $data->ProviderReturnMessage ?? null;
        $this->returnCode = $data->ReturnCode ?? null;
        $this->returnMessage = $data->ReturnMessage ?? null;

        if (isset($data->VoidSplitPayments)) {
            foreach ($data->VoidSplitPayments as $split) {
                $splitPayments = new VoidSplitPayments();
                $newSplit = $splitPayments->populate($split);
                $this->addVoidSplitPayments($newSplit);
            }
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
