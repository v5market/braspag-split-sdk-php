<?php

namespace Braspag\Split\Request;

use Braspag\Split\Domains\Environment;
use Braspag\Split\Domains\Authentication;
use Braspag\Split\Domains\Sale\Settlements as SettlementObj;

class Settlements extends BaseRequest
{
    protected $url_sandbox = "https://splitsandbox.braspag.com.br/";
    protected $url_production = "https://split.braspag.com.br/";

    /**
     * Cria nova instância
     *
     * @param Environment $Environment
     * @param Authentication $merchantKey
     */
    public function __construct(Environment $Environment, Authentication $authentication)
    {
        parent::__construct($Environment, $authentication);
    }

    /**
     * Realiza bloqueio conforme as regras definidas
     *
     * @param string $paymentId
     * @param SettlementObj $value
     */
    public function register($paymentId, SettlementObj $value)
    {
        $accessToken = $this->authentication->accessToken();
        $this->request->addHeader('Authorization', "Bearer $accessToken");

        $result = $this->request->put("/api/transactions/$paymentId/settlements", json_encode($value));
        $json = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $obj = new \stdClass();
            $obj->values = $json;

            $settlementObj = new SettlementObj();
            $settlementObj->setResponseRaw($result->getResponse());
            return $settlementObj->populate($obj);
        }

        $this->throwError();
    }
}
