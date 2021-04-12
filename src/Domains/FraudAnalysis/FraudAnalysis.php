<?php

declare(strict_types=1);

namespace Braspag\Split\Domains\FraudAnalysis;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Domains\Environment;
use Braspag\Split\Domains\Cart\Cart;
use Braspag\Split\Constants\FraudAnalysis\FraudAnalysis as Constants;

class FraudAnalysis implements BraspagSplit
{
    private $id;
    private $status;
    private $sequence;
    private $sequenceCriteria;
    private $provider;
    private $captureOnLowRisk;
    private $voidOnHighRisk;
    private $totalOrderAmount;
    private $fingerPrintId;
    private $browser;
    private $cart;
    private $merchantDefinedFields = [];
    private $shipping;
    private $fraudAnalysisReasonCode;
    private $replyData;

    /**
     * Id da transação no AntiFraude Braspag
     *
     * @param string $value
     */
    public function setId(string $value)
    {
        $this->id = $value;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Status da transação no AntiFraude Braspag
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-14-payment.fraudanalysis.status
     *
     * @param integer $value
     */
    public function setStatus(int $value)
    {
        $this->status = $value;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getStatusText()
    {
        return isset(Constants::STATUSES[$this->status])
            ? Constants::STATUSES[$this->status]
            : null;
    }

    /**
     * Tipo de fluxo da análise de fraude
     * Valores possíveis: Constants::SEQUENCE_*
     *
     * @param string $value
     */
    public function setSequence(string $value)
    {
        if (!in_array($value, Constants::SEQUENCES)) {
            throw new \InvalidArgumentException('Sequence is invalid. See Constants::SEQUENCE_*', 5000);
        }

        $this->sequence = $value;
    }

    /**
     * @return string
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Critério do fluxo da análise de fraude
     *
     * @param string $value
     */
    public function setSequenceCriteria(string $value)
    {
        $this->sequenceCriteria = $value;
    }

    /**
     * @return string
     */
    public function getSequenceCriteria()
    {
        return $this->sequenceCriteria;
    }

    /**
     * Provedor de AntiFraude
     *
     * @param string $value
     */
    public function setProvider(string $value)
    {
        $this->provider = $value;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Indica se a transação após a análise de fraude será capturada
     *
     * @param mixed $value Se o valor não for booleano, converte-o
     */
    public function setCaptureOnLowRisk($value)
    {
        $this->captureOnLowRisk = !!$value;
    }

    /**
     * @return boolean
     */
    public function getCaptureOnLowRisk()
    {
        return $this->captureOnLowRisk;
    }

    /**
     * Indica se a transação após a análise de fraude será cancelada
     *
     * @param mixed $value Se o valor não for booleano, converte-o
     */
    public function setVoidOnHighRisk($value)
    {
        $this->voidOnHighRisk = !!$value;
    }

    /**
     * @return boolean
     */
    public function getVoidOnHighRisk()
    {
        return $this->voidOnHighRisk;
    }

    /**
     * Valor total do pedido em centavos
     * Ex: 123456 = R$ 1.234,56
     *
     * @param integer $value
     */
    public function setTotalOrderAmount(int $value)
    {
        $this->totalOrderAmount = $value;
    }

    /**
     * @return integer
     */
    public function getTotalOrderAmount()
    {
        return $this->totalOrderAmount;
    }

    /**
     * Identificador utilizado para cruzar informações obtidas do dispositivo do comprador.
     * Este mesmo identificador deve ser utilizado para gerar o valor que será atribuído ao
     * campo session_id do script que será incluído na página de checkout.
     * Obs.: Este identificador poderá ser qualquer valor ou o número do pedido, mas deverá
     * ser único durante 48 horas
     *
     * @param string $value
     */
    public function setFingerPrintId(string $value)
    {
        $this->fingerPrintId = $value;
    }

    /**
     * @return string
     */
    public function getFingerPrintId()
    {
        return $this->fingerPrintId;
    }

    /**
     * Informa os dados do navegador
     *
     * @param Browser $value
     */
    public function setBrowser(Browser $value)
    {
        $this->browser = $value;
    }

    /**
     * @return Browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Informa os dados do carrinho de compra
     *
     * @param Cart $value
     */
    public function setCart(Cart $value)
    {
        $this->cart = $value;
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-20-payment.fraudanalysis.merchantdefinedfields
     *
     * @param integer $id ID das informações adicionais a serem enviadas
     * @param string $value Valor das informações adicionais a serem enviadas
     */
    public function addMerchantDefinedFields(int $id, string $value)
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('Merchant defined fields id is inválid. ' .
            'Use a number value greater than 0.', 5001);
        }

        $this->merchantDefinedFields[] = [
            "Id" => $id,
            "Value" => $value
        ];
    }

    /**
     * @return array
     */
    public function getMerchantDefinedFields()
    {
        return $this->merchantDefinedFields;
    }

    /**
     * Nome completo do responsável a receber o produto no endereço de entrega
     *
     * @param string $value
     */
    public function setShippingAddressee($value)
    {
        $this->shipping["Addressee"] = $value;
    }

    /**
     * @return string|null
     */
    public function getShippingAddressee()
    {
        return isset($this->shipping["Addressee"])
            ? $this->shipping["Addressee"]
            : null;
    }

    /**
     * Meio de entrega do pedido
     *
     * @param string $value
     */
    public function setShippingMethod(string $value)
    {
        $value = strtolower($value);

        if (!in_array($value, Constants::SHIPPING_METHODS)) {
            throw new \OutOfBoundsException('Shipping method is invalid. See Card::SHIPPING_METHODS', 5002);
        }

        $this->shipping["Method"] = $value;
    }

    /**
     * @return string Retorna o método de envio
     */
    public function getShippingMethod(): ?string
    {
        return isset($this->shipping["Method"])
            ? $this->shipping["Method"]
            : null;
    }

    /**
     * Número do telefone do responsável a receber o produto no endereço de entrega
     *
     * @param string $value
     */
    public function setShippingPhone(string $value)
    {
        $value = preg_replace('/\D/', '', $value);

        $this->shipping["Phone"] = $value;
    }

    /**
     * @return string
     */
    public function getShippingPhone(): ?string
    {
        return isset($this->shipping["Phone"])
            ? $this->shipping["Phone"]
            : null;
    }

    /**
     * @return string
     */
    public static function orgId(Environment $env): string
    {
        return $env->isSandbox()
            ? Constants::ORG_ID_SANDBOX
            : Constants::ORG_ID_PRODUCTION;
    }

    /**
     * Cria Session ID
     *
     * @param string $provider Identificador da sua loja na Cybersource.
     *    Caso não possua, entre em contato com a Braspag
     * @param string $fingerPrint Identificador utilizado para cruzar
     *    informações obtidas do dispositivo do comprador.
     *
     * @return string
     */
    public static function sessionId(string $provider, string $fingerPrint): string
    {
        return "$provider$fingerPrint";
    }

    /**
     * Código de retorno da Cybersouce
     *
     * @param integer $value
     */
    public function setFraudAnalysisReasonCode(int $value)
    {
        $this->fraudAnalysisReasonCode = $value;
    }

    /**
     * @return integer
     */
    public function getFraudAnalysisReasonCode()
    {
        return $this->fraudAnalysisReasonCode;
    }

    /**
     * @param Replydata $value
     */
    public function setReplyData(ReplyData $value)
    {
        $this->replyData = $value;
    }

    /**
     * @return Replydata
     */
    public function getReplyData()
    {
        return $this->replyData;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $data = [];

        $values = get_object_vars($this);

        foreach ($values as $key => $value) {
            $key = ucfirst($key);

            if ($value instanceof BraspagSplit) {
                $value = $value->toArray();
            }

            $data[$key] = $value;
        }

        return array_filter($data);
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->id = $data->Id ?? null;
        $this->status = $data->Status ?? null;
        $this->sequence = $data->Sequence ?? null;
        $this->sequenceCriteria = $data->SequenceCriteria ?? null;
        $this->provider = $data->Provider ?? null;
        $this->captureOnLowRisk = $data->CaptureOnLowRisk ?? null;
        $this->voidOnHighRisk = $data->VoidOnhighRisk ?? null;
        $this->totalOrderAmount = $data->TotalOrderAmount ?? null;
        $this->fingerPrintId = $data->FingerPrintId ?? null;
        $this->fraudAnalysisReasonCode = $data->FraudAnalysisReasonCode ?? null;
        $this->shipping = isset($data->Shipping) ? (array)$data->Shipping : [];

        if (isset($data->MerchantDefinedFields)) {
            $this->merchantDefinedFields = json_decode(json_encode($data->MerchantDefinedFields), true);
        }

        if (isset($data->Browser)) {
            $this->browser = new Browser();
            $this->browser->populate($data->Browser);
        }

        if (isset($data->Cart)) {
            $this->cart = new Cart();
            $this->cart->populate($data->Cart);
        }

        if (isset($data->ReplyData)) {
            $this->replyData = new ReplyData();
            $this->replyData->populate($data->ReplyData);
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
