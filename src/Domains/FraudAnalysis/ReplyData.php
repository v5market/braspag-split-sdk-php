<?php

namespace Braspag\Split\Domains\FraudAnalysis;

use Braspag\Split\Interfaces\BraspagSplit;

class ReplyData implements BraspagSplit
{
    private $addressInfoCode;
    private $factorCode;
    private $score;
    private $binCountry;
    private $cardIssuer;
    private $cardScheme;
    private $hostSeverity;
    private $internetInfoCode;
    private $ipRoutingMethod;
    private $scoreModelUsed;
    private $casePriority;
    private $providerTransactionId;

    /**
     * Códigos indicam incompatibilidades entre os endereços de cobrança e entrega do comprador
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-16-payment.fraudanalysis.replydata.addressinfocode
     *
     * @return string
     */
    public function getAddressInfoCode()
    {
        return $this->addressInfoCode;
    }

    /**
     * Códigos que afetaram a pontuação da análise
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-17-payment.fraudanalysis.replydata.factorcode
     *
     * @return string
     */
    public function getFactorCode()
    {
        return $this->factorCode;
    }

    /**
     * Score da análise de fraude. Valor entre 0 e 100
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Código do país do BIN do cartão usado na análise.
     * Mais informações em [ISO 2-Digit Alpha Country Code](https://www.iso.org/obp/ui)
     *
     * @return string
     */
    public function getBinCountry()
    {
        return $this->binCountry;
    }

    /**
     * Nome do banco ou entidade emissora do cartão de crédito
     *
     * @return string
     */
    public function getCardIssuer()
    {
        return $this->cardIssuer;
    }

    /**
     * Bandeira do cartão
     *
     * @return string
     */
    public function getCardScheme()
    {
        return $this->cardScheme;
    }

    /**
     * Nível de risco do domínio de e-mail do comprador, de 0 a 5, onde 0 é
     * risco indeterminado e 5 representa o risco mais alto
     *
     * @return integer
     */
    public function getHostSeverity()
    {
        return $this->hostSeverity;
    }

    /**
     * Códigos que indicam problemas com o endereço de e-mail, o endereço IP
     * ou o endereço de cobrança
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-18-payment.fraudanalysis.replydata.internetinfocode
     *
     * @return string
     */
    public function getInternetInfoCode()
    {
        return $this->internetInfoCode;
    }

    /**
     * Método de roteamento do comprador obtido a partir do endereço de IP
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-19-payment.fraudanalysis.replydata.iproutingmethod
     *
     * @return string
     */
    public function getIpRoutingMethod()
    {
        return $this->ipRoutingMethod;
    }

    /**
     * Nome do modelo de score utilizado na análise. Caso não tenha nenhum modelo
     * definido, o modelo padrão da Cybersource foi o utilizado
     *
     * @return string
     */
    public function getScoreModelUsed()
    {
        return $this->scoreModelUsed;
    }

    /**
     * Define o nível de prioridade das regras ou perfis do lojista. O nível de
     * prioridade varia de 1 (maior) a 5 (menor) e o valor padrão é 3, e este
     * será atribuído caso não tenha definido a prioridade das regras ou perfis.
     * Este campo somente será retornado se a loja for assinante do Enhanced Case
     * Management
     *
     * @return integer
     */
    public function getCasePriority()
    {
        return $this->casePriority;
    }

    /**
     * Id da transação na Cybersource
     *
     * @return string
     */
    public function getProviderTransactionId()
    {
        return $this->providerTransactionId;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);

        return array_combine($keys, $values);
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->addressInfoCode = $data->AddressInfoCode ?? null;
        $this->factorCode = $data->FactorCode ?? null;
        $this->score = $data->Score ?? null;
        $this->binCountry = $data->BinCountry ?? null;
        $this->cardIssuer = $data->CardIssuer ?? null;
        $this->cardScheme = $data->CardScheme ?? null;
        $this->hostSeverity = $data->HostSeverity ?? null;
        $this->internetInfoCode = $data->InternetInfoCode ?? null;
        $this->ipRoutingMethod = $data->IpRoutingMethod ?? null;
        $this->scoreModelUsed = $data->ScoreModelUsed ?? null;
        $this->casePriority = $data->CasePriority ?? null;
        $this->providerTransactionId = $data->ProviderTransactionId ?? null;

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
