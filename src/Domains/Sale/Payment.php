<?php

namespace Braspag\Split\Domains\Sale;

use DateTime;
use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Domains\{
    FraudAnalysis\FraudAnalysis,
    FraudAnalysis\FraudAlert
};
use Braspag\Split\Domains\PaymentMethod\{
    Boleto,
    AbstractCard,
    CreditCard,
    DebitCard
};
use Braspag\Split\Constants\{
    Sale\Currency,
    Sale\Interest,
    Card\Type as CardType
};
use Braspag\Split\Traits\Sale\{
    ProviderReturn,
    ReasonReturn
};
use Braspag\Split\Traits\Request\Response;

class Payment implements BraspagSplit
{
    use ProviderReturn;
    use ReasonReturn;
    use Response;

    private $provider;
    private $type;
    private $amount;
    private $serviceTaxAmount;
    private $currency;
    private $country;
    private $installments;
    private $interest;
    private $capture;
    private $authenticate;
    private $recurrent;
    private $softDescriptor;
    private $returnUrl;
    private $doSplit;
    private $authenticationUrl;
    private $extraDataCollection = [];
    private $fraudAnalysis;
    private $splitPayments = [];
    private $externalAuthentication;
    private $paymentId;
    private $proofOfSale;
    private $acquirerTransactionId;
    private $authorizationCode;
    private $receivedDate;
    private $capturedDate;
    private $capturedAmount;
    private $eci;
    private $status;
    private $refund;
    private $chargebacks;
    private $fraudAlert;
    private $transaction;
    private $splitTransaction;
    private $reasonCode;
    private $reasonMessage;
    private $providerReturnCode;
    private $providerReturnMessage;

    /**
     * Define o nome da provedora da autorização.
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
     * Define o tipo do meio de pagamento.
     * Obs.: Somente o tipo CreditCard funciona com análise de fraude
     *
     * @param string $value
     */
    public function setType(string $value)
    {
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
     * Define o valor da transação financeira em centavos.
     * Ex: 150000 = R$ 1.500,00
     *
     * @param integer $value
     */
    public function setAmount(int $value)
    {
        $this->amount = $value;
    }

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Indica se a autorização deverá ser com captura automática
     * Obs.: Deverá verificar junto à adquirente a disponibilidade desta funcionalidade
     * Obs2.: Este campo deverá ser preenchido de acordo com o fluxo da análise de fraude
     *
     * @param mixed $value Caso o valor não seja um booleano, converte-o
     */
    public function setCapture($value)
    {
        $this->capture = !!$value;
    }

    /**
     * @return boolean
     */
    public function getCapture()
    {
        return $this->capture;
    }

    /**
     * Indica se a transação deve ser autenticada
     *
     * @param mixed $value Se não for booleano, converte-o
     */
    public function setAuthenticate($value)
    {
        $this->authenticate = !!$value;
    }

    /**
     * @return boolean
     */
    public function getAuthenticate()
    {
        return $this->authenticate;
    }

    /**
     * Indica se a transação é do tipo recorrente
     *
     * @param mixed $value Se não for booleano, converte-o
     */
    public function setRecurrent($value)
    {
        $this->recurrent = !!$value;
    }

    /**
     * @return boolean
     */
    public function getRecurrent()
    {
        return $this->recurrent;
    }

    /**
     * País na qual o pagamento será realizado
     *
     * @param string $value
     */
    public function setCountry(string $value)
    {
        if (mb_strlen($value) !== 3) {
            throw new \LengthException('Payment country invalid. Use the ISO 3166-1 alfa-3 ' .
            'pattern.\nEg: BRA = Brazil, USA = United States, DEU = Germany', 7000);
        }

        $this->country = $value;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Aplicável apenas para empresas aéreas. Montante do valor da autorização que
     * deve ser destinado à taxa de serviço
     * Obs.: Esse valor não é adicionado ao valor da autorização
     *
     * @param integer $value
     */
    public function setServiceTaxAmount(int $value)
    {
        $this->serviceTaxAmount = $value;
    }

    /**
     * @return integer
     */
    public function getServiceTaxAmount()
    {
        return $this->serviceTaxAmount;
    }

    /**
     * Moeda na qual o pagamento será feito
     * Possíveis valores ver em Currency::CURRENCIES
     *
     * @param string $value
     */
    public function setCurrency(string $value)
    {
        $value = strtoupper($value);

        if (!in_array($value, Currency::CURRENCIES)) {
            throw new \OutOfBoundsException('Card brand is invalid. See Currency::CURRENCIES', 7001);
        }

        $this->currency = $value;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Tipo de parcelamento
     * Possíveis valores ver em self::INTEREST_*
     *
     * @param string $value
     */
    public function setInterest(string $value)
    {
        if (!in_array($value, Interest::INTERESTS)) {
            throw new \OutOfBoundsException('Payment interest is invalid. See Payment::INTEREST_*', 7002);
        }

        $this->interest = $value;
    }

    /**
     * @return string
     */
    public function getInterest()
    {
        return $this->interest;
    }

    /**
     * Número de parcelas
     *
     * @param integer $value
     */
    public function setInstallments(int $value)
    {
        $this->installments = $value;
    }

    /**
     * @return integer
     */
    public function getInstallments()
    {
        return $this->installments;
    }

    /**
     * Texto que será impresso na fatura do portador
     *
     * @param string $value
     */
    public function setSoftDescriptor(string $value)
    {
        $this->softDescriptor = $value;
    }

    /**
     * @return string
     */
    public function getSoftDescriptor()
    {
        return $this->softDescriptor;
    }

    /**
     * Url de retorno do lojista. URL para onde o lojista vai ser redirecionado no final do fluxo.
     *
     * @param string $value
     */
    public function setReturnUrl(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Url must valid.', 7003);
        }

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
     * URL para qual o Lojista deve redirecionar o Cliente para o fluxo de Débito.
     *
     * @param string $value;
     */
    public function setAuthenticationUrl(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Authentication url is invalid.', 7004);
        }

        $this->authenticationUrl = $value;
    }

    /**
     * @return string
     */
    public function getAuthenticationUrl()
    {
        return $this->authenticationUrl;
    }

    /**
     * Indica se a transação será dividida entre vários participantes
     *
     * @param mixed $value Caso o valor não seja um booleano, converte-o
     */
    public function setDoSplit($value)
    {
        $this->doSplit = !!$value;
    }

    /**
     * @return boolean
     */
    public function getDoSplit()
    {
        return !!$this->doSplit;
    }

    /**
     * @see self::addExtraDataCollection
     */
    public function setExtraDataCollection(array $values)
    {
        $this->extraDataCollection = [];

        foreach ($values as $value) {
            $this->addExtraDataCollection($value["Name"], $value["Value"]);
        }
    }

    /**
     * Define campos personalizados
     *
     * @param string $name Identificador do campo extra que será enviado
     * @param string $value Valor do campo extra que será enviado
     */
    public function addExtraDataCollection($name, $value)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Extra data collection Name is required', 7005);
        }

        $this->extraDataCollection[] = [
            'Name' => $name,
            'Value' => $value,
        ];
    }

    /**
     * @return array
     */
    public function getExtraDataCollection()
    {
        return $this->extraDataCollection;
    }

    /**
     * Define a forma de pagametno
     */
    public function setPaymentMethod($value)
    {
        $this->paymentMethod = $value;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Adiciona as regras da análise antifraude.
     *
     * @param FraudAnalysis $value
     */
    public function setFraudAnalysis(FraudAnalysis $value)
    {
        $this->fraudAnalysis = $value;
    }

    /**
     * @return FraudAnalysis
     */
    public function getFraudAnalysis()
    {
        return $this->fraudAnalysis;
    }

    public function addSplitPayments(SplitPayments $value)
    {
        $this->splitPayments[] = $value;
    }

    /**
     * @param SplitPayments[] $value
     */
    public function setSplitPayments(array $values)
    {
        $this->splitPayments = [];

        foreach ($values as $value) {
            $this->addSplitPayments($value);
        }
    }

    /**
     * @return SplitPayments[]
     */
    public function getSplitPayments()
    {
        return $this->splitPayments;
    }

    /**
     * @param ExternalAuthentication $value
     */
    public function setExternalAuthentication(ExternalAuthentication $value)
    {
        $this->externalAuthentication = $value;
    }

    /**
     * @return ExternalAuthentication
     */
    public function getExternalAuthentication()
    {
        return $this->externalAuthentication;
    }

    /**
     * Identificador da transação no Pagador Braspag
     *
     * @return string
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Identificador da transação na adquirente
     *
     * @return string
     */
    public function getAcquirerTransactionId()
    {
        return $this->acquirerTransactionId;
    }

    /**
     * Número do comprovante de venda na adquirente
     * (NSU - Número sequencial único da transação)
     *
     * @return string
     */
    public function getProofOfSale()
    {
        return $this->proofOfSale;
    }

    /**
     * Código de autorização na adquirente
     *
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * Data em que a transação foi recebida no Pagador Braspag
     *
     * @return DateTime
     */
    public function getReceivedDate()
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $this->receivedDate);
    }

    /**
     * Data em que a transação foi capturada na adquirente
     *
     * @return DateTime
     */
    public function getCapturedDate()
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $this->capturedDate);
    }

    /**
     * Valor capturado da transação
     *
     * @return integer
     */
    public function getCapturedAmount()
    {
        return $this->capturedAmount;
    }

    /**
     * Eletronic Commerce Indicator. Código gerado em uma
     * transação de crédito com autenticação externa
     *
     * @return string
     */
    public function getEci()
    {
        return $this->eci;
    }

    /**
     * Status da transação no Pagador
     * https://braspag.github.io/manual/split-pagamentos-braspag-pagador#tabela-21-payment.status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Refund
     */
    public function getRefund()
    {
        return $this->refund;
    }

    /**
     * @return Chargeback[]
     */
    public function getChargebacks()
    {
        return $this->chargebacks;
    }

    /**
     * @return FraudAlert
     */
    public function getFraudAlert()
    {
        return $this->fraudAlert;
    }

    /**
     * @return string
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @return string
     */
    public function getSplitTransaction()
    {
        return $this->splitTransaction;
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
     * @return int
     */
    public function getProviderReturnCode()
    {
        return $this->providerReturnCode;
    }

    /**
     * @return string
     */
    public function getProviderReturnMessage()
    {
        return $this->providerReturnMessage;
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

            if ($value instanceof CreditCard) {
                $key = 'CreditCard';
            } elseif ($value instanceof DebitCard) {
                $key = 'DebitCard';
            } elseif ($value instanceof Boleto) {
                $data = array_merge($data, $value->toArray());
                $value = null;
            }

            if ($value instanceof BraspagSplit) {
                $value = $value->toArray();
            } elseif ($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $data[$key] = $value;
        }

        return array_filter($data, function ($item) {
            return $item !== null && $item !== [];
        });
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->provider = $data->Provider ?? null;
        $this->type = $data->Type ?? null;
        $this->amount = $data->Amount ?? null;
        $this->serviceTaxAmount = $data->ServiceTaxAmount ?? null;
        $this->currency = $data->Currency ?? null;
        $this->country = $data->Country ?? null;
        $this->installments = $data->Installments ?? null;
        $this->interest = $data->Interest ?? null;
        $this->capture = $data->Capture ?? null;
        $this->authenticate = $data->Authenticate ?? null;
        $this->recurrent = $data->Recurrent ?? null;
        $this->softDescriptor = $data->SoftDescriptor ?? null;
        $this->doSplit = $data->DoSplit ?? null;
        $this->extraDataCollection = isset($data->ExtraDataCollection)
            ? json_decode(json_encode($data->ExtraDataCollection), true)
            : null;
        $this->returnUrl = $data->ReturnUrl ?? null;
        $this->authenticationUrl = $data->AuthenticationUrl ?? null;
        $this->status = $data->Status ?? null;
        $this->capturedAmount = isset($data->CapturedAmount) ? intval($data->CapturedAmount) : null;
        $this->authorizationCode = $data->AuthorizationCode ?? null;
        $this->proofOfSale = $data->ProofOfSale ?? null;
        $this->acquirerTransactionId = $data->AcquirerTransactionId ?? null;
        $this->paymentId = $data->PaymentId ?? null;
        $this->eci = $data->ECI ?? null;
        $this->reasonCode = $data->ReasonCode ?? null;
        $this->reasonMessage = $data->ReasonMessage ?? null;
        $this->providerReturnCode = $data->ProviderReturnCode ?? null;
        $this->providerReturnMessage = $data->ProviderReturnMessage ?? null;

        if (isset($data->Refund)) {
            $this->refund = new Refund();
            $this->refund->populate($data->Refund);
        }

        if (isset($data->FraudAlert)) {
            $this->fraudAlert = new FraudAlert();
            $this->fraudAlert->populate($data->FraudAlert);
        }

        if (isset($data->ReceivedDate)) {
            $this->receivedDate = DateTime::createFromFormat('Y-m-d H:i:s', $data->ReceivedDate);
        }

        if (isset($data->CapturedDate)) {
            $this->capturedDate = DateTime::createFromFormat('Y-m-d H:i:s', $data->CapturedDate);
        }

        if (isset($data->Chargebacks) && !empty($data->Chargebacks)) {
            foreach ($this->chargebacks as $chargeback) {
                $chargebackObj = new Chargeback();
                $chargebackObj->populate($chargeback);
                $this->chargebacks[] = $chargebackObj;
            }
        };

        $paymentMethod = null;
        $type = strtolower($data->Type ?? '');

        if ($type == 'creditcard') {
            $paymentMethod = new CreditCard();
            $paymentMethod->populate($data->CreditCard);
        } elseif ($type == 'debitcard') {
            $paymentMethod = new DebitCard();
            $paymentMethod->populate($data->DebitCard);
        } elseif ($type == 'boleto') {
            $paymentMethod = new Boleto();
            $paymentMethod->populate($data);
        }

        if ($paymentMethod) {
            $this->paymentMethod = $paymentMethod;
        }

        if (isset($data->FraudAnalysis)) {
            $fraudAnalysis = new FraudAnalysis();
            $fraudAnalysis->populate($data->FraudAnalysis);
            $this->fraudAnalysis = $fraudAnalysis;
        }

        if (isset($data->SplitPayments) && !empty($data->SplitPayments)) {
            foreach ($data->SplitPayments as $split) {
                $splitPayments = new SplitPayments();
                $splitPayments->populate($split);
                $this->addSplitPayments($splitPayments);
            }
        }

        if (isset($data->Transaction) && !empty($data->Transaction)) {
            $transaction = new SplitTransaction();
            $transaction->populate($data->Transaction);
            $this->transaction = $transaction;
        }

        if (isset($data->SplitTransaction) && !empty($data->SplitTransaction)) {
            $splitTransaction = new SplitTransaction();
            $splitTransaction->populate($data->SplitTransaction);
            $this->splitTransaction = $splitTransaction;
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
