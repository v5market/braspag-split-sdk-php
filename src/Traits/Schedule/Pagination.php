<?php

namespace Braspag\Split\Traits\Schedule;

trait Pagination
{
    private $pageCount;
    private $pageSize;
    private $pageIndex;

    /**
     * @return integer
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * @return integer
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @return integer
     */
    public function getPageIndex()
    {
        return $this->pageIndex;
    }
}
