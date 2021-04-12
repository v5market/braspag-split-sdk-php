<?php

namespace Braspag\Split\Domains;

use Braspag\Split\Interfaces\BraspagSplit;

class Address implements BraspagSplit
{
    private $street;
    private $number;
    private $complement;
    private $neighborhood;
    private $city;
    private $state;
    private $zipCode;
    private $district;
    private $country;

    /**
     * @param string $value
     */
    public function setStreet(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Street is required', 1000);
        }

        $this->street = $value;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $value
     */
    public function setNumber(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Address number is required', 1001);
        }

        $this->number = $value;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $value
     */
    public function setComplement(string $value)
    {
        $this->complement = $value;
    }

    /**
     * @return string
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * @param string $value
     */
    public function setNeighborhood(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Neighborhood is required', 1002);
        }

        $this->neighborhood = $value;
    }

    /**
     * @return string
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * @param string $value
     */
    public function setCity(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('City is required', 1003);
        }

        $this->city = $value;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $value
     */
    public function setState(string $value)
    {
        if (mb_strlen($value) != 2) {
            throw new \LengthException('The state must be 2 characters', 1004);
        }

        $this->state = $value;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $value
     */
    public function setZipcode(string $value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (mb_strlen($value) != 8) {
            throw new \LengthException('The zipcode must be 8 characters', 1006);
        }

        $this->zipCode = $value;
    }

    /**
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $value
     */
    public function setDistrict(string $value)
    {
        $this->district = $value;
    }

    /**
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param string $value
     */
    public function setCountry(string $value)
    {
        if (mb_strlen($value) > 3) {
            throw new \LengthException('The country must be 3 characters', 1007);
        }

        $this->country = $value;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $arr = array_filter(get_object_vars($this));
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);

        return array_combine($keys, $values);
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $data)
    {
        $this->street = $data->Street ?? null;
        $this->number = $data->Number ?? null;
        $this->neighborhood = $data->Neighborhood ?? null;
        $this->city = $data->City ?? null;
        $this->state = $data->State ?? null;
        $this->zipCode = $data->ZipCode ?? null;
        $this->complement = $data->Complement ?? null;
        $this->country = $data->Country ?? null;
        $this->district = $data->District ?? null;

        return $this;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
