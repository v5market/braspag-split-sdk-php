<?php

namespace Braspag\Split\Request;

use Braspag\Split\Domains\Environment;

class Request
{
    private const USER_AGENT = 'Braspag Split SDK PHP 1.0.0';

    /** @var string */
    private $curl = null;
    private $curl_info;
    private $curl_error;
    private $response;
    private $baseApi;
    private $requestBody;
    private $environment;

    /** @var array */
    private $headers = [];

    /**
     * Constructor
     *
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;

        $this->init();
    }

    /**
     * Configura o curl com os valores padrão
     */
    private function init()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, !$this->environment->isSandbox());
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
    }

    /**
     * Adiciona HEADER à requisição
     *
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = "$key: $value";
        return $this;
    }

    /**
     * Envia uma requisição GET
     *
     * @param string $path
     * @param array $data
     *
     * @return self
     */
    public function get($path, $data = [])
    {
        $this->setRequestBody($data);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');

        if (count($data) > 0) {
            curl_setopt($this->curl, CURLOPT_URL, $this->buildUrl($path) . '?' . http_build_query($data));
        } else {
            curl_setopt($this->curl, CURLOPT_URL, $this->buildUrl($path));
        }

        return $this->execute();
    }

    /**
     * Faz uma requisiao do tipo POST
     *
     * @param string $path
     * @param mixed $data
     *
     * @return self
     */
    public function post($path, $data)
    {
        $this->setRequestBody($data);
        curl_setopt($this->curl, CURLOPT_URL, $this->buildUrl($path));
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        return $this->execute();
    }

    /**
     * Faz uma requisiao do tipo PUT
     *
     * @param string $path
     * @param mixed $data
     *
     * @return self
     */
    public function put($path, $data = '')
    {
        $this->setRequestBody($data);
        curl_setopt($this->curl, CURLOPT_URL, $this->buildUrl($path));
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        return $this->execute();
    }

    /**
     * @return string|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Retorna as informações da requisiao
     *
     * @return array
     */
    public function getInfo()
    {
        return $this->curl_info;
    }

    /**
     * Retorna um erro da requisição, caso haja
     */
    public function getError()
    {
        return $this->curl_error;
    }

    /**
     * Retorna o "Status Code"
     *
     * @return int
     */
    public function getHttpStatus()
    {
        return $this->curl_info['http_code'];
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getHttpStatus() >= 200 && $this->getHttpStatus() < 300;
    }

    /**
     * Was an 'error' returned (client error or server error).
     *
     * @return bool
     */
    public function isError()
    {
        return $this->getHttpStatus() >= 400 && $this->getHttpStatus() < 600;
    }

    /**
     * @return bool
     */
    public function isClientError()
    {
        return $this->getHttpStatus() >= 400 && $this->getHttpStatus() < 500;
    }

    /**
     * @return bool
     */
    public function isServerError()
    {
        return $this->getHttpStatus() >= 500 && $this->getHttpStatus() < 500;
    }

    /**
     * Define o hostname
     *
     * @param string value
     *
     * @return self
     *
     * @throws \InvalidArgumentException Se a URL for inválida
     */
    public function setBaseApi($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Base API Invalid.');
        }
        $this->baseApi = $value;
        return $this;
    }

    /**
     * Armazena os dados enviados
     *
     * @param mixed $value
     *
     * @return self
     */
    public function setRequestBody($value)
    {
        $this->requestBody = serialize($value);

        return $this;
    }

    /**
     * Retorna os dados enviados
     *
     * @return mixed
     */
    public function getRequestBody()
    {
        return !empty($this->requestBody)
            ? unserialize($this->requestBody)
            : null;
    }

    /**
     * Executa a requisição
     *
     * @return self
     */
    private function execute()
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);

        $this->response = curl_exec($this->curl);
        $this->curl_info = curl_getinfo($this->curl);
        $this->curl_error = curl_error($this->curl);
        curl_close($this->curl);

        return $this;
    }

    /**
     * Monta a URL de acordo com o ambiente (sandbox ou produção)
     *
     * @param string $path caminho da URl
     *
     * @return string
     */
    private function buildUrl($path)
    {
        $baseApi = ($this->baseApi) ? $this->baseApi : $this->environment->getUrl();
        return sprintf('%s%s', $baseApi, preg_replace('/^\//', '', $path));
    }
}
