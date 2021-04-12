<?php

namespace Braspag\Split\Domains\Schedule;

use Braspag\Split\Interfaces\BraspagSplit;
use Braspag\Split\Traits\Request\Response;
use Braspag\Split\Traits\Schedule\Pagination;

class Events implements BraspagSplit
{
    use Pagination;
    use Response;

    private $schedules = [];

    /**
     * @return Schedule[]
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $schedules = [];

        foreach ($this->schedules as $schedule) {
            $schedules[] = $schedule->toArray();
        }

        return [
            "PageCount" => $this->pageCount,
            "PageSize" => $this->pageSize,
            "PageIndex" => $this->pageIndex,
            "Schedules" => $schedules,
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

        foreach ($data->Schedules as $schedule) {
            $newSchedule = new Schedule();
            $newSchedule->populate($schedule);
            $this->schedules[] = $newSchedule;
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
