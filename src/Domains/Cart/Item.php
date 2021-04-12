<?php

namespace Braspag\Split\Domains\Cart;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Constants\Cart\Item as Constants;

class Item implements BraspagSplit
{
    private $giftCategory;
    private $hostHedge = Constants::VALUE_NORMAL;
    private $nonSensicalHedge = Constants::VALUE_NORMAL;
    private $obscenitiesHedge = Constants::VALUE_NORMAL;
    private $phoneHedge = Constants::VALUE_NORMAL;
    private $name;
    private $quantity;
    private $sku;
    private $unitPrice;
    private $risk;
    private $timeHedge = Constants::VALUE_NORMAL;
    private $type = 'Default';
    private $velocityHedge = Constants::VALUE_NORMAL;

    /**
     * Identifica que avaliará os endereços de cobrança e entrega para diferentes cidades, estados ou países
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-1-payment.fraudanalysis.cart.tems[n].giftcategory
     *
     * @param string $value
     */
    public function setGiftCategory(string $value)
    {
        $value = strtolower($value);

        if (!in_array($value, Constants::VALUES_GIFT_CATEGORIES)) {
            throw new \InvalidArgumentException('Gift category is invalid. ' .
            'See \Braspag\Split\Constants\Cart\Item::VALUES_GIFT_CATEGORIES', 3000);
        }

        $this->giftCategory = $value;
    }

    /**
     * @return string
     */
    public function getGiftCategory()
    {
        return $this->giftCategory;
    }

    /**
     * Nível de importância dos endereços de IP e e-mail do comprador na análise de fraude
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-2-payment.fraudanalysis.cart.items[n].hosthedge
     *
     * @param string $value
     */
    public function setHostHedge(string $value)
    {
        $value = strtolower($value);

        if (!in_array($value, Constants::VALUES_IMPORTANCE_LEVEL)) {
            throw new \InvalidArgumentException('Host hedge is invalid. See ' .
            '\Braspag\Split\Constants\Cart\Item::VALUES_IMPORTANCE_LEVEL', 3001);
        }

        $this->hostHedge = $value;
    }

    /**
     * @return string
     */
    public function getHostHedge()
    {
        return $this->hostHedge;
    }

    /**
     * Nível de importância das verificações sobre os dados do comprador sem
     * sentido na análise de fraude
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-3-payment.fraudanalysis.cart.items[n].nonsensicalhedge
     *
     * @param string $value
     */
    public function setNonSensicalHedge(string $value)
    {
        $value = strtolower($value);

        if (!in_array($value, Constants::VALUES_IMPORTANCE_LEVEL)) {
            throw new \InvalidArgumentException('Non sensical hedge is invalid. ' .
            'See \Braspag\Split\Constants\Cart\Item::VALUES_IMPORTANCE_LEVEL', 3002);
        }

        $this->nonSensicalHedge = $value;
    }

    /**
     * @return string
     */
    public function getNonSensicalHedge()
    {
        return $this->nonSensicalHedge;
    }

    /**
     * Nível de importância das verificações sobre os dados do comprador com
     * obscenidade na análise de fraude
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-4-payment.fraudanalysis.cart.items[n].obscenitieshedge
     *
     * @param string $value
     */
    public function setObscenitiesHedge(string $value)
    {
        $value = strtolower($value);

        if (!in_array($value, Constants::VALUES_IMPORTANCE_LEVEL)) {
            throw new \InvalidArgumentException(
                'Obscenities hedge is invalid. See Constants::VALUES_IMPORTANCE_LEVEL',
                3003
            );
        }

        $this->obscenitiesHedge = $value;
    }

    /**
     * @return string
     */
    public function getObscenitiesHedge()
    {
        return $this->obscenitiesHedge;
    }

    /**
     * Nível de importância das verificações sobre os números de telefones do
     * comprador na análise de fraude
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-5-payment.fraudanalysis.cart.items[n].phonehedge
     *
     * @param string $value
     */
    public function setPhoneHedge(string $value)
    {
        $value = strtolower($value);

        if (!in_array($value, Constants::VALUES_IMPORTANCE_LEVEL)) {
            throw new \InvalidArgumentException(
                'Phone hedge is invalid. See Constants::VALUES_IMPORTANCE_LEVEL',
                3004
            );
        }

        $this->phoneHedge = $value;
    }

    /**
     * @return string
     */
    public function getPhoneHedge()
    {
        return $this->phoneHedge;
    }

    /**
     * Nome do Produto
     *
     * @param string $value
     */
    public function setName(string $value)
    {
        $this->name = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Quantidade do produto
     *
     * @param int $value
     */
    public function setQuantity(int $value)
    {
        $this->quantity = $value;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * SKU (Stock Keeping Unit - Unidade de Controle de Estoque) do produto
     *
     * @param string $value
     */
    public function setSku(string $value)
    {
        $this->sku = $value;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Preço unitário do produto
     *
     * @param int $value
     */
    public function setUnitPrice(int $value)
    {
        $this->unitPrice = $value;
    }

    /**
     * @return int
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Nível de risco do produto associado a quantidade de chargebacks
     *
     * @param string $value
     */
    public function setRisk(string $value)
    {
        $this->risk = strtolower($value);
    }

    /**
     * @return string
     */
    public function getRisk()
    {
        return $this->risk;
    }

    /**
     * Nível de importância da hora do dia na análise de fraude que o comprador
     * realizou o pedido
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-7-payment.fraudanalysis.cart.items[n].timehedge
     *
     * @param string $value
     */
    public function setTimeHedge(string $value)
    {
        $value = strtolower($value);

        if (!in_array($value, Constants::VALUES_IMPORTANCE_LEVEL)) {
            throw new \InvalidArgumentException('Phone hedge is invalid. ' .
            'See \Braspag\Split\Constants\Cart\Item::VALUES_IMPORTANCE_LEVEL', 3005);
        }

        $this->timeHedge = $value;
    }

    /**
     * @return string
     */
    public function getTimeHedge()
    {
        return $this->timeHedge;
    }

    /**
     * Categoria do produto
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-8-payment.fraudanalysis.cart.items[n].type
     *
     * @param string $value
     */
    public function setType(string $value)
    {
        $value = strtolower($value);

        if (!in_array($value, Constants::TYPES)) {
            throw new \InvalidArgumentException('Cart item type is invalid. See Constants::TYPES', 3006);
        }

        $this->type = $value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Nível de importância da frequência de compra do comprador na análise de fraude dentros dos 15 minutos anteriores
     * @see https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-9-payment.fraudanalysis.cart.items[n].velocityhedge
     *
     * @param string $value
     */
    public function setVelocityHedge(string $value)
    {
        $value = strtolower($value);

        if (!in_array($value, Constants::VALUES_IMPORTANCE_LEVEL)) {
            throw new \InvalidArgumentException(
                'Velocity hedge is invalid. See Constants::VALUES_IMPORTANCE_LEVEL',
                3007
            );
        }

        $this->velocityHedge = $value;
    }

    /**
     * @return string
     */
    public function getVelocityHedge()
    {
        return $this->velocityHedge;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);

        return array_filter(array_combine($keys, $values));
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->giftCategory = $data->GiftCategory ?? null;
        $this->hostHedge = $data->HostHedge ?? null;
        $this->nonSensicalHedge = $data->NonSensicalHedge ?? null;
        $this->obscenitiesHedge = $data->ObscenitiesHedge ?? null;
        $this->phoneHedge = $data->PhoneHedge ?? null;
        $this->name = $data->Name ?? null;
        $this->sku = $data->Sku ?? null;
        $this->risk = $data->Risk ?? null;
        $this->timeHedge = $data->TimeHedge ?? null;
        $this->type = $data->Type ?? null;
        $this->velocityHedge = $data->VelocityHedge ?? null;
        $this->unitPrice = isset($data->UnitPrice) ? intval($data->UnitPrice) : null;
        $this->quantity = isset($data->Quantity) ? intval($data->Quantity) : null;

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
