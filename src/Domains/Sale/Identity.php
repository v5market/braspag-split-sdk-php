<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Traits\Document;
use Braspag\Split\Interfaces\BraspagSplit;

class Identity implements BraspagSplit
{
    use Document;

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->type = ucfirst(strtolower($data->DocumentType));
        $this->value = $data->DocumentNumber;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return [
            "Identity" => $this->value,
            "IdentityType" => $this->type
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
