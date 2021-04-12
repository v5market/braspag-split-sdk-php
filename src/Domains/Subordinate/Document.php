<?php

namespace Braspag\Split\Domains\Subordinate;

use Braspag\Split\Validation\Validator;
use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Document as TraitDocument;

/**
 * @todo Utilizar o método __callStatic para torna dinâmico
 */
class Document implements BraspagSplit
{
    use TraitDocument;

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return [
            "DocumentNumber" => $this->value,
            "DocumentType" => $this->type
        ];
    }

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
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
