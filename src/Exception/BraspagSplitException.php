<?php

namespace Braspag\Split\Exception;

use Throwable;
use Braspag\Split\Request\Request;

class BraspagSplitException extends \Exception
{
    private $request;

    /**
     * Cria Instância
     *
     * @param string $message
     * @param Request $request
     * @param int $code
     * @param \Throwable $previous
     */
    public function __construct(string $message, Request $request = null, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
    }

    /**
     * Captura a instância da requisição
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Captura a resposta da API
     *
     * @return mixed
     */
    public function getResponse()
    {
        return !empty($this->request)
            ? $this->request->getResponse()
            : null;
    }

    /**
     * Captura o _Status Code_ da requisição
     *
     * @return int
     */
    public function getStatusCode()
    {
        return !empty($this->request)
            ? $this->request->getHttpStatus()
            : null;
    }

    /**
     * Captura os dados enviados à requisiao
     *
     * @return mixed
     */
    public function getRequestBody()
    {
        return !empty($this->request)
            ? $this->request->getRequestBody()
            : null;
    }
}
