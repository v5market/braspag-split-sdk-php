<?php

namespace Braspag\Split\Interfaces;

interface BraspagSplit extends \JsonSerializable
{
    /**
     * Retorna os dados da classe no formato array
     *
     * @return array
     */
    public function toArray();

    /**
     * Retorna os dados da classe no formato json
     *
     * @return string
     */
    public function jsonSerialize();

    /**
     * Cria uma nova instância da classe conforme
     * os dados informado em `$data`
     *
     * @param \stdClass $data
     *
     * @return self|null
     */
    public function populate(\stdClass $data);
}
