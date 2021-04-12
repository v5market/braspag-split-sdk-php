<?php

namespace Braspag\Split\Request;

use Braspag\Split\Domains\Environment;
use Braspag\Split\Exception\BraspagSplitException;

class ThreeDSecurity extends BaseRequest
{
    private const URL_ACCESS_TOKEN_SANDBOX = 'https://mpisandbox.braspag.com.br/';
    private const URL_ACCESS_TOKEN = 'https://mpi.braspag.com.br/';

    /**
     * @param Environment $env
     */
    public function __construct(Environment $env)
    {
        if ($env->isSandbox()) {
            $this->url_sandbox = self::URL_ACCESS_TOKEN_SANDBOX;
        } else {
            $this->url_production = self::URL_ACCESS_TOKEN;
        }

        parent::__construct($env);
    }

    /**
     * Solicita o token de acesso
     *
     * @throws BraspagSplitException
     *
     * @return object
     */
    public function generateAccessToken(
        string $establishmentCode,
        string $merchantName,
        string $mcc
    ) {
        $data = [
            'EstablishmentCode' => $establishmentCode,
            'MerchantName' => $merchantName,
            'MCC' => $mcc,
        ];

        $this->request->addHeader('Authorization', 'Basic ' . $this->Environment->getAuthorization());
        $this->request->post('v2/auth/token', json_encode($data));

        if ($this->request->isSuccess()) {
            $response = $this->request->getResponse();
            return json_decode($response);
        }

        $this->throwError();
    }
}
