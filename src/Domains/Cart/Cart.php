<?php

namespace Braspag\Split\Domains\Cart;

use Braspag\Split\Interfaces\BraspagSplit;

class Cart implements BraspagSplit
{
    private $isGift;
    private $returnsAccepted;
    private $items = [];

    /**
     * Indica se o pedido realizado pelo comprador é para presente
     *
     * @param mixed $value Se o valor não for booleano, converte-o
     */
    public function setIsGift($value)
    {
        $this->isGift = !!$value;
    }

    /**
     * @return boolean
     */
    public function getIsGift(): bool
    {
        return $this->isGift();
    }

    /**
     * @return boolean
     */
    public function isGift(): bool
    {
        return $this->isGift;
    }

    /**
     * Indica se o pedido realizado pelo comprador pode ser devolvido a loja
     *
     * @param mixed $value Se o valor não for booleano, converte-o
     */
    public function setReturnsAccepted($value)
    {
        $this->returnsAccepted = !!$value;
    }

    /**
     * @return boolean
     */
    public function getReturnsAccepted(): bool
    {
        return $this->returnsAccepted;
    }

    /**
     * Adiciona um item ao carrinho
     *
     * @param Item $value
     */
    public function addItem(Item $value)
    {
        $this->items[] = $value;
    }

    /**
     * Adiciona os itens ao carrinho
     *
     * @param Item[] $values
     */
    public function setItems($values)
    {
        $this->items = [];

        foreach ($values as $value) {
            $this->addItem($value);
        }
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $data = [];

        if (isset($this->isGift)) {
            $data["IsGift"] = $this->isGift;
        }

        if (isset($this->returnsAccepted)) {
            $data["ReturnsAccepted"] = $this->returnsAccepted;
        }

        foreach ($this->items as $item) {
            $data["Items"][] = $item->toArray();
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->IsGift)) {
            $this->setIsGift($data->IsGift);
        }

        if (isset($data->ReturnsAccepted)) {
            $this->setReturnsAccepted($data->ReturnsAccepted);
        }

        if (isset($data->Items)) {
            foreach ($data->Items as $value) {
                $item = new Item();
                $item->populate($value);

                $this->addItem($item);
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
