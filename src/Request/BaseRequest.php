<?php

namespace Braspag\Split\Request;

use Braspag\Split\Domains\Environment;
use Braspag\Split\Domains\Authentication;
use Braspag\Split\Exception\BraspagSplitException;

class BaseRequest
{
    /** @var string */
    protected $url_sandbox;

    /** @var string */
    protected $url_production;

    /** @var Environment */
    protected $Environment;

    /** @var Authentication */
    protected $authentication;

    /** @var Request */
    protected $request;

    /** @var string */
    protected $merchantKey;

    /**
     * Cria nova instância
     *
     * @param Environment $Environment
     * @param Authentication|null $authentication
     * @param string|null $merchantKey
     */
    public function __construct(Environment $Environment, Authentication $authentication = null, $merchantKey = null)
    {
        $this->Environment = $Environment;
        $this->authentication = $authentication;
        $this->merchantKey = $merchantKey;

        $this->request = new Request($this->Environment);
        $this->request->setBaseApi($this->buildBaseApi());
        $this->request->addHeader('Content-Type', "application/json");
    }

    /**
     * Retorna a URL da API conforme o ambiente
     * Sandbox ou Produção
     *
     * @return string
     */
    protected function buildBaseApi()
    {
        return $this->Environment->isSandbox()
            ? $this->url_sandbox
            : $this->url_production;
    }

    /**
     * Lança um erro erro
     *
     * @throws RuntimeException Quando o servidor estiver fora do ar (Error 500)
     * @throws UnexpectedValueException Quando o servidor retornar um erro na requisição
     */
    protected function throwError()
    {
        $message = '';

        if ($this->request->isServerError()) {
            $message = print_r($this->request, true);
        } elseif ($this->request->getError()) {
            $message = $this->request->getError();
        } elseif (!empty($this->request->getResponse())) {
            $message = $this->request->getResponse();
        } else {
            $message = print_r($this->request, true);
        }

        throw new BraspagSplitException($message, $this->request);
    }
}
