<?php

namespace Braspag\Split\Domains\Schedule;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Schedule\Pagination;

class Transactions implements BraspagSplit
{
    use Pagination;

    private $transactions = [];

    /**
     * @return Transaction[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $transactions = [];

        foreach ($this->transactions as $transaction) {
            $transactions[] = $transaction->toArray();
        }

        return [
            "PageCount" => $this->pageCount,
            "PageSize" => $this->pageSize,
            "PageIndex" => $this->pageIndex,
            "Transactions" => $transactions,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->pageCount = $data->PageCount;
        $this->pageSize = $data->PageSize;
        $this->pageIndex = $data->PageIndex;

        foreach ($data->Transactions as $transaction) {
            $newTransaction = new Transaction();
            $newTransaction->populate($transaction);
            $this->transactions[] = $newTransaction;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
