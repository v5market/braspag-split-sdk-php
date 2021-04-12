<?php

namespace Braspag\Split\Request;

use Braspag\Split\Domains\Environment;
use Braspag\Split\Domains\Sale\Sale;

class Search extends BaseRequest
{
    protected $url_sandbox = "https://apiquerysandbox.braspag.com.br/";
    protected $url_production = "https://apiquery.braspag.com.br/";

    /**
     * Cria nova instância
     *
     * @param Environment $Environment
     * @param string $merchantKey
     */
    public function __construct(Environment $Environment, $merchantKey)
    {
        parent::__construct($Environment, null, $merchantKey);
        $this->request->addHeader('MerchantId', $Environment->getClientId());
        $this->request->addHeader('MerchantKey', $merchantKey);
    }

    /**
     * Busca um pagamento através do ID
     *
     * @param string $paymentId
     *
     * @return Sale
     */
    public function byPaymentId($paymentId)
    {
        $result = $this->request->get("/v2/sales/$paymentId");
        $json = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $sale = new Sale();
            $sale->setResponseRaw($result->getResponse());
            return $sale->populate($json);
        }

        $this->throwError();
    }
}
