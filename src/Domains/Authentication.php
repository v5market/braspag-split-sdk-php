<?php

namespace Braspag\Split\Domains;

use Braspag\Split\Request\Request;

class Authentication implements \JsonSerializable
{
    public const API_SANDBOX = "https://authsandbox.braspag.com.br/";
    public const API_PRODUCTION = "https://auth.braspag.com.br/";

    /** @var Environment */
    private $Environment;

    /** @var string */
    private $clientId;
    private $clientSecret;
    private $accessToken;
    private $expiresIn;
    private $tokenType;

    /**
     * @param Environment $Environment
     */
    public function __construct(Environment $Environment, array $accessToken = [])
    {
        $this->Environment = $Environment;
        $this->setClientId($Environment->getClientId());
        $this->setClientSecret($Environment->getClientSecret());

        if (!empty($accessToken)) {
            $this->accessToken = $accessToken["access_token"];
            $this->expiresIn = $accessToken["expires_in"];
            $this->tokenType = $accessToken["token_type"];
        }
    }

    /**
     * Define access_token salvo
     *
     * @param string $value
     */
    public function setAccessToken(string $value)
    {
        $this->accessToken = $value;
    }

    /**
     * @param string $value
     */
    public function setClientId(string $value)
    {
        $this->clientId = $value;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $value
     */
    public function setClientSecret(string $value)
    {
        $this->clientSecret = $value;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Captura o Acess Token com base nos dados do cliente
     *
     * @return string|null
     *
     * @throws \UnexpectedValueException
     */
    public function accessToken()
    {
        if ($this->accessToken && time() < $this->expiresIn) {
            return $this->accessToken;
        }

        $authorization = $this->Environment->getAuthorization();

        $request = new Request($this->Environment);
        $request->setBaseApi($this->getBaseApiAuthentication());
        $request->addHeader('Authorization', "Basic $authorization");
        $request->addHeader('Content-Type', "application/x-www-form-urlencoded");
        $result = $request->post('/oauth2/token', 'grant_type=client_credentials');
        $json = json_decode($result->getResponse());

        if ($result->isSuccess()) {
            if (!isset($json->access_token)) {
                throw new \UnexpectedValueException($json->error_description);
            }

            $this->accessToken = $json->access_token;
            $this->expiresIn = time() + intval($json->expires_in);
            $this->tokenType = $json->token_type;

            return $json->access_token;
        } elseif ($result->isError()) {
            $error = $result->getResponse();

            throw new \UnexpectedValueException($error);
        }

        return null;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            "access_token" => $this->accessToken,
            "expires_in" => $this->expiresIn,
            "token_type" => $this->tokenType
        ];
    }

    /**
     * @return string Retorna URL de acordo com o ambiente de
     *    desenvolvimento
     */
    private function getBaseApiAuthentication()
    {
        return $this->Environment->isSandbox()
            ? self::API_SANDBOX
            : self::API_PRODUCTION;
    }
}
