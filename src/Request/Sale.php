<?php

namespace Braspag\Split\Request;

use Braspag\Split\Domains\Authentication;
use Braspag\Split\Domains\Environment;
use Braspag\Split\Domains\Sale\Capture;
use Braspag\Split\Domains\Sale\SplitPayments;
use Braspag\Split\Domains\Sale\Payment;
use Braspag\Split\Domains\Sale\Sale as SaleObj;

class Sale extends BaseRequest
{
    protected $url_sandbox = "https://apisandbox.braspag.com.br/";
    protected $url_production = "https://api.braspag.com.br/";

    protected $url_query_sandbox = "https://apiquerysandbox.braspag.com.br/";
    protected $url_query_production = "https://apiquery.braspag.com.br/";

    protected $url_split_sandbox = "https://splitsandbox.braspag.com.br/";
    protected $url_split_production = "https://split.braspag.com.br/";

    /**
     * Cria nova instância
     *
     * @param Environment $Environment
     * @param string $merchantKey
     */
    public function __construct(Environment $Environment, string $merchantKey, Authentication $auth = null)
    {
        parent::__construct($Environment, $auth, $merchantKey);

        $this->request->addHeader('MerchantID', $this->Environment->getClientId());
        $this->request->addHeader('MerchantKey', $merchantKey);
    }

    /**
     * Cria um pedido
     *
     * @param SaleObj $json
     *
     * @return SaleObj Retorna novo objeto
     */
    public function create(SaleObj $value)
    {
        $result = $this->request->post("/v2/sales/", json_encode($value));
        $json = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $newSale = new SaleObj();
            $newSale->setResponseRaw($result->getResponse());
            return $newSale->populate($json);
        }

        $this->throwError();
    }

    /**
     * Captura uma transação
     *
     * @param string $paymentId
     * @param int|SplitPayments[] $amount
     * @param SplitPayments[] $splits
     *
     * @return Capture
     */
    public function capture(string $paymentId, $amount = null, array $splits = [])
    {
        $path = "/v2/sales/$paymentId/capture";
        $data = [];

        if ($amount && is_int($amount)) {
            $path .= "?amount=$amount";
        } else {
            $splits = $amount;
        }

        if ($splits) {
            $data["SplitPayments"] = $splits;
        }

        $result = $this->request->put($path, json_encode($data));
        $json = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $newCapture = new Capture();
            $newCapture->setResponseRaw($result->getResponse());
            return $newCapture->populate($json);
        }

        $this->throwError();
    }

    /**
     * Cancela um determinado pedido ou valor de um vendedor
     *
     * @param string $paymentId
     * @param int $amount
     * @param SplitPayments[] $splits
     *
     * @return Capture
     */
    public function cancel(string $paymentId, int $amount = null, array $splits = null)
    {
        $path = "/v2/sales/$paymentId/void";
        $data = [];

        if ($amount) {
            $path .= "?amount=$amount";
        }

        if ($splits) {
            $data['VoidSplitPayments'] = $splits;
        }

        $result = $this->request->put($path, json_encode($data));
        $json = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $newCapture = new Capture();
            $newCapture->setResponseRaw($result->getResponse());
            return $newCapture->populate($json);
        }

        $this->throwError();
    }

    /**
     * Captura as informações de uma transação
     *
     * @param string $paymentId
     *
     * @return SaleObj|null
     */
    public function info(string $paymentId)
    {
        $this->request->setBaseApi($this->buildBaseApi(1));
        $result = $this->request->get("/v2/sales/$paymentId");
        $response = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $sale = new SaleObj();
            $sale->setResponseRaw($result->getResponse());
            return $sale->populate($response);
        }

        if ($result->getHttpStatus() === 404) {
            return null;
        }

        $this->throwError();
    }

    /**
     * Envia split
     *
     * @param string $paymentId
     * @param SplitPayments[] $splits
     */
    public function split(string $paymentId, array $splits, ?string $masterRateDiscountType = null)
    {
        $path = "/api/transactions/$paymentId/split";

        if ($masterRateDiscountType) {
            $path .= "?masterRateDiscountType=$masterRateDiscountType";
        }

        $accessToken = $this->authentication->accessToken();
        $this->request->addHeader('Authorization', "Bearer $accessToken");

        $this->request->setBaseApi($this->buildBaseApi(2));
        $result = $this->request->put($path, json_encode($splits));
        $response = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $payment = new Payment();
            $payment->populate($response);
            $payment->setResponseRaw($result->getResponse());
            return $payment;
        }

        $this->throwError();
    }

    /**
     * {@inheritDoc}
     */
    protected function buildBaseApi(int $type = 0)
    {
        if ($type === 1) {
            return $this->Environment->isSandbox()
                ? $this->url_query_sandbox
                : $this->url_query_production;
        }

        if ($type === 2) {
            return $this->Environment->isSandbox()
                ? $this->url_split_sandbox
                : $this->url_split_production;
        }

        return $this->Environment->isSandbox()
            ? $this->url_sandbox
            : $this->url_production;
    }
}
