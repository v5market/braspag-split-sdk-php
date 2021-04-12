<?php

namespace Braspag\Split\Domains;

class Environment
{
    public const URL_SANDBOX = "https://splitsandbox.braspag.com.br/";
    public const URL_PRODUCTION = "https://split.braspag.com.br/";

    private $url;
    private $clientId;
    private $clientSecret;
    private $isSandbox;

    private function __constructor()
    {
        /** Previning */
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     */
    public static function sandbox($clientId, $clientSecret)
    {
        $instance = new Environment();
        $instance->url = self::URL_SANDBOX;
        $instance->clientId = $clientId;
        $instance->clientSecret = $clientSecret;
        $instance->isSandbox = true;
        return $instance;
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     */
    public static function production($clientId, $clientSecret)
    {
        $instance = new Environment();
        $instance->url = self::URL_PRODUCTION;
        $instance->clientId = $clientId;
        $instance->clientSecret = $clientSecret;
        $instance->isSandbox = false;
        return $instance;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return boolean
     */
    public function isSandbox()
    {
        return $this->isSandbox;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Retorna o clientId e clientSecret codificados em base64
     *
     * @return string
     */
    public function getAuthorization()
    {
        return base64_encode(sprintf("%s:%s", $this->clientId, $this->clientSecret));
    }
}
