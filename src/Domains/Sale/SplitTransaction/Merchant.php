<?php

namespace Braspag\Split\Domains\Sale\SplitTransaction;

use Braspag\Split\Interfaces\BraspagSplit;

class Merchant implements BraspagSplit
{
    /** @var string (Apenas Leitura) */
    private $id;

    /** @var int (Apenas Leitura) */
    private $typeId;

    /** @var string (Apenas Leitura) */
    private $type;

    /** @var string (Apenas Leitura) */
    private $fancyName;

    /** @var string (Apenas Leitura) */
    private $corporateName;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @return string
     */
    public function getFancyName()
    {
        return $this->fancyName;
    }

    /**
     * @return string
     */
    public function getCorporateName()
    {
        return $this->corporateName;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);

        return array_filter(array_combine($keys, $values), function ($item) {
            return $item !== null;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->id = $data->Id ?? null;
        $this->typeId = $data->TypeId ?? null;
        $this->type = $data->Type ?? null;
        $this->fancyName = $data->FancyName ?? null;
        $this->corporateName = $data->CorporateName ?? null;

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
