<?php

namespace Braspag\Split\Domains\Subordinate;

use Braspag\Split\Interfaces\BraspagSplit;

class Notification implements BraspagSplit
{
    private $url;

    private $headers = [];

    /**
     * @param string $value
     */
    public function setUrl(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Url $value is not valid", 14000);
        }

        $this->url = $value;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $headers = [];

        foreach ($this->headers as $key => $value) {
            $headers["Headers"][] = [
                "Key" => $key,
                "Value" => $value
            ];
        }

        return array_merge([
            "Url" => $this->url
        ], $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        if (isset($data->Url)) {
            $this->setUrl($data->Url);
        }

        if (isset($data->Headers)) {
            foreach ($data->Headers as $header) {
                $this->addHeader($header->Key, $header->Value);
            }
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
