<?php

namespace Braspag\Split\Request;

use Braspag\Split\Domains\Environment;
use Braspag\Split\Domains\Authentication;
use Braspag\Split\Domains\Chargeback\Chargeback as ChargebackObj;

class Chargeback extends BaseRequest
{
    protected $url_sandbox = "https://apisandbox.braspag.com.br/";
    protected $url_production = "https://api.braspag.com.br/";

    /**
     * Cria nova instância
     *
     * @param Environment $Environment
     * @param string $merchantKey
     */
    public function __construct(Environment $Environment, $merchantKey)
    {
        parent::__construct($Environment, null, $merchantKey);

        $this->request->addHeader('MerchantID', $Environment->getClientId());
        $this->request->addHeader('MerchantKey', $merchantKey);
    }

    /**
     * No Split de Pagamentos o Marketplace pode definir se assumirá o chargeback
     * ou o repassará para seus Subordinados, desde que acordado previamente
     * entre as partes.
     *
     * @param string $chargebackId
     * @param ChargebackSplitPayments[] $splits
     */
    public function splits($chargebackId, array $splits = [])
    {
        $result = $this->request->post("/api/chargebacks/$chargebackId/splits", json_encode($splits));
        $json = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $chageback = new ChargebackObj();
            $chageback->setResponseRaw($result->getResponse());
            return $chageback->populate($json);
        }

        $this->throwError();
    }
}
