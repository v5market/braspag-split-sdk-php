<?php

namespace Braspag\Split\Request;

use Braspag\Split\Domains\Environment;
use Braspag\Split\Domains\Authentication;
use Braspag\Split\Domains\Schedule\Events;
use Braspag\Split\Domains\Schedule\Transactions;
use Braspag\Split\Domains\Schedule\Transaction;
use Braspag\Split\Domains\Schedule\Filter;

class Schedule extends BaseRequest
{
    protected $url_sandbox = "https://splitsandbox.braspag.com.br/";
    protected $url_production = "https://split.braspag.com.br/";

    /**
     * @param Environment $env
     * @param Authentication $auth
     */
    public function __construct(Environment $env, Authentication $auth)
    {
        parent::__construct($env, $auth);
    }

    /**
     * Consultar o que uma loja tem a receber.
     *
     * @param Filter $filter
     *
     * @return Events
     */
    public function getEvents(Filter $filter = null)
    {
        $query = (string)$filter;

        $accessToken = $this->authentication->accessToken();
        $this->request->addHeader('Authorization', "Bearer $accessToken");

        $this->request->get('/schedule-api/events?' . $query);

        if ($this->request->isSuccess()) {
            $json = json_decode($this->request->getResponse());
            $events = new Events();
            $events->setResponseRaw($this->request->getResponse());
            return $events->populate($json);
        }

        $this->throwError();
    }

    /**
     * Consultar a agenda financeira de várias transações ou de uma transação específica.
     *
     * @param Filter|string|null $value Payment ID
     * @param Filter|null $filter
     *
     * @return Events|Transaction Quando o paymentId for definido, retorna Transactions;
     *      caso contrário, Events.
     */
    public function getTransactions($value = null, ?Filter $filter = null)
    {
        $path = '/schedule-api/transactions';

        if ($value instanceof Filter) {
            $filter = $value;
            $value = null;
        }

        if ($value) {
            $path .= "/$value";
        }

        if ($filter) {
            $path .= '?' . (string)$filter;
        }

        $accessToken = $this->authentication->accessToken();
        $this->request->addHeader('Authorization', "Bearer $accessToken");

        $this->request->get($path);

        if ($this->request->isSuccess()) {
            $json = json_decode($this->request->getResponse());

            if ($value === null) {
                $transactions = new Transactions();
                return $transactions->populate($json);
            }

            $transaction = new Transaction();
            return $transaction->populate($json);
        }

        $this->throwError();
    }
}
