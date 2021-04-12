<?php

namespace Braspag\Split\Domains\Sale;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Request\Response;

/**
 * Este bloqueio, conhecido como custódia, pode durar até 180 dias. Após este prazo, a Braspag
 * liquidará o valor para o subordinado independentemente do bloqueio.
 */
class Settlements implements BraspagSplit
{
    use Response;

    private $settlements = [];

    /**
     * Informa os subordinados e a opção de bloqueio
     *
     * @see Settlements::add
     *
     * @return self
     */
    public function setSettlements(array $values)
    {
        $this->settlements = [];

        foreach ($values as $value) {
            $this->add($value['SubordinateMerchantId'], $value['Locked']);
        }
    }

    /**
     * @return array
     */
    public function getSettlements()
    {
        return $this->settlements;
    }

    /**
     * Informa o subordinado e a opção de bloqueio
     *
     * @param string $subordinateMerchantId
     * @param bool $locked
     *
     * @return self
     */
    public function add(string $subordinateMerchantId, bool $locked)
    {
        $this->settlements[] = [
            "SubordinateMerchantId" => $subordinateMerchantId,
            "Locked" => $locked
        ];

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->settlements;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        foreach ($data->values as $value) {
            $this->add($value->SubordinateMerchantId, $value->Locked);
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
