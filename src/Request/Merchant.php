<?php

namespace Braspag\Split\Request;

use Braspag\Split\Request\Request;
use Braspag\Split\Domains\Environment;
use Braspag\Split\Domains\Authentication;
use Braspag\Split\Domains\Subordinate\Merchant as MerchantObj;

class Merchant extends BaseRequest
{
    protected $url_sandbox = "https://splitonboardingsandbox.braspag.com.br/";
    protected $url_production = "https://splitonboarding.braspag.com.br/";

    /**
     * Cria nova instância
     *
     * @param Environment $Environment
     * @param Authentication $authentication
     */
    public function __construct(Environment $Environment, Authentication $authentication)
    {
        parent::__construct($Environment, $authentication);
    }

    /**
     * Cadastra um vendedor
     *
     * @param MerchantObj $merchant
     *
     * @throws \UnexpectedValueException Quando o servidor retornar um erro
     * @throws \RuntimeException Quando o servidor estiver fora do ar
     *
     * @return MerchantObj Nova instância de MerchantoBJ
     */
    public function register(MerchantObj $merchant)
    {
        $accessToken = $this->authentication->accessToken();
        $this->request->addHeader('Authorization', "Bearer $accessToken");

        $result = $this->request->post('/api/subordinates', json_encode($merchant));
        $json = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $newMerchant = new MerchantObj();
            $newMerchant->setResponseRaw($result->getResponse());
            return $newMerchant->populate($json);
        }

        $this->throwError();
    }

    /**
     * Captura um vendedor
     *
     * @param string $merchantId
     *
     * @throws \UnexpectedValueException Quando o servidor retornar um erro
     * @throws \RuntimeException Quando o servidor estiver fora do ar
     *
     * @return MerchantObj|null Nova instância de MerchantoBJ
     */
    public function capture($merchantId)
    {
        $accessToken = $this->authentication->accessToken();
        $this->request->addHeader('Authorization', "Bearer $accessToken");

        $result = $this->request->get("/api/subordinates/$merchantId");
        $json = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            $newMerchant = new MerchantObj();
            $newMerchant->setResponseRaw($result->getResponse());
            return $newMerchant->populate($json);
        }

        $this->throwError();
    }
}
