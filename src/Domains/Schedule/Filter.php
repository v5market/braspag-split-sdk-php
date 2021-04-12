<?php

namespace Braspag\Split\Domains\Schedule;

use DateTime;

class Filter
{
    private $initialForecastedDate;
    private $finalForecastedDate;
    private $initialPaymentDate;
    private $finalPaymentDate;
    private $pageIndex;
    private $pageSize;
    private $eventStatus;
    private $includeAllSubordinates;
    private $merchantIds = [];
    private $initialCaptureDate;
    private $finalCaptureDate;

    /**
     * @return DateTime
     */
    public function getFinalCaptureDate()
    {
        return DateTime::createFromFormat('Y-m-d', $this->finalCaptureDate);
    }

    /**
     * @param DateTime|string $value
     *
     * @return self
     */
    public function setFinalCaptureDate($value)
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d');
        }

        $this->finalCaptureDate = $value;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getInitialCaptureDate()
    {
        return DateTime::createFromFormat('Y-m-d', $this->initialCaptureDate);
    }

    /**
     * @param DateTime|string $value
     *
     * @return self
     */
    public function setInitialCaptureDate($value)
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d');
        }

        $this->initialCaptureDate = $value;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getMerchantIds()
    {
        return $this->merchantIds;
    }

    /**
     * @param string[] $value
     *
     * @return self
     */
    public function setMerchantIds(array $value)
    {
        $this->merchantIds = $value;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function addMerchantId(string $value)
    {
        $this->merchantIds[] = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIncludeAllSubordinates()
    {
        return $this->includeAllSubordinates === 'true';
    }

    /**
     * @param bool $value
     *
     * @return self
     */
    public function setIncludeAllSubordinates(bool $value)
    {
        $this->includeAllSubordinates = $value ? 'true' : 'false';

        return $this;
    }

    /**
     * @return string
     */
    public function getEventStatus()
    {
        return $this->eventStatus;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setEventStatus(string $value)
    {
        $this->eventStatus = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param int $value
     *
     * @return self
     */
    public function setPageSize(int $value)
    {
        $this->pageSize = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageIndex()
    {
        return $this->pageIndex;
    }

    /**
     * @param int $value
     *
     * @return self
     */
    public function setPageIndex(int $value)
    {
        $this->pageIndex = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getFinalPaymentDate()
    {
        return DateTime::createFromFormat('Y-m-d', $this->finalPaymentDate);
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setFinalPaymentDate(string $value)
    {
        $this->finalPaymentDate = $value;

        return $this;
    }

    /**
     * @return DateTime|string
     */
    public function getInitialPaymentDate()
    {
        return DateTime::createFromFormat('Y-m-d', $this->initialPaymentDate);
    }

    /**
     * @param DateTime|string $value
     *
     * @return self
     */
    public function setInitialPaymentDate($value)
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d');
        }

        $this->initialPaymentDate = $value;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getFinalForecastedDate()
    {
        return DateTime::createFromFormat('Y-m-d', $this->finalForecastedDate);
    }

    /**
     * @param DateTime|string $value
     *
     * @return self
     */
    public function setFinalForecastedDate($value)
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d');
        }

        $this->finalForecastedDate = $value;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getInitialForecastedDate()
    {
        return DateTime::createFromFormat('Y-m-d', $this->initialForecastedDate);
    }

    /**
     * @param DateTime|string $value
     *
     * @return self
     */
    public function setInitialForecastedDate($value)
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d');
        }

        $this->initialForecastedDate = $value;

        return $this;
    }

    /**
     * Retorna os filtros
     */
    public function __toString()
    {
        $value = get_object_vars($this);

        if (!empty($value['merchantIds'])) {
            $value['merchantIds'] = implode('&merchantIds=', $value['merchantIds']);
        }

        $value = array_filter($value);
        $value = http_build_query($value);

        return urldecode($value);
    }
}
