<?php

namespace Braspag\Split\Domains\Subordinate;

use Braspag\Split\Interfaces\BraspagSplit;

class Analysis implements BraspagSplit
{
    private $status;
    private $score;
    private $denialReason;

    /**
     * Status da análise do processo de KYC. Os Status válidos são:
     *  - Approved
     *  - ApprovedWithRestriction
     *  - Rejected
     *
     * @param string $value
     */
    public function setStatus(string $value)
    {
        $this->status = $value;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param integer $value Score da análise do processo de KYC. Range de 1 a 100
     */
    public function score(int $value)
    {
        $this->score = $value;
    }

    /**
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param string $value Motivo de reprovação do subordinado
     */
    public function setDenialReason(string $value)
    {
        $this->denialReason = $value;
    }

    /**
     * @return integer
     */
    public function getDenialReason()
    {
        return $this->denialReason;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $vars = get_object_vars($this);
        $result = [];

        foreach ($vars as $key => $value) {
            $key = ucfirst($key);

            $result[$key] = $value;
        }

        return array_filter($result);
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->Status)) {
            $this->status = $data->Status;
        }

        if (isset($data->Score)) {
            $this->score = (int)$data->Score;
        }

        if (isset($data->DenialReason)) {
            $this->denialReason = (int)$data->DenialReason;
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
