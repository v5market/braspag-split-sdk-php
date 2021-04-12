<?php

namespace Braspag\Split\Request;

use Braspag\Split\Domains\Environment;
use Braspag\Split\Domains\Authentication;
use Braspag\Split\Domains\Schedule\Adjustment as AdjustmentObj;

class Adjustment extends BaseRequest
{
    protected $url_sandbox = "https://splitsandbox.braspag.com.br/";
    protected $url_produciton = "https://split.braspag.com.br/";

    public function __construct(Environment $env, Authentication $auth)
    {
        parent::__construct($env, $auth);
    }

    /**
     * @param AdjustmentObj $adjustment
     */
    public function register(AdjustmentObj $adjustment)
    {
        $accessToken = $this->authentication->accessToken();
        $this->request->addHeader('Authorization', "Bearer $accessToken");

        $this->request->post('/adjustment-api/adjustments/', json_encode($adjustment));

        if ($this->request->isSuccess()) {
            $response = $this->request->getResponse();
            $json = json_decode($response);
            $newAdjustment = new AdjustmentObj();
            $newAdjustment->setResponseRaw($response);
            return $newAdjustment->populate($json);
        }

        $this->throwError();
    }
}
