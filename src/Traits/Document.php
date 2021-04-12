<?php

namespace Braspag\Split\Traits;

use Braspag\Split\Validation\Validator;

trait Document
{
    private $type;
    private $value;

    /**
     * Cria uma nova instancia do Document
     * com o tipo definido como CPF
     *
     * @param string $value
     *
     * @return Identity
     */
    public static function cpf($value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (!Validator::cpf()->validator($value)) {
            throw new \InvalidArgumentException("CPF $value is invalid");
        }

        $instance = new self();
        $instance->type = 'Cpf';
        $instance->value = $value;

        return $instance;
    }

    /**
     * Cria uma nova instancia do Document
     * com o tipo definido como CNPJ
     *
     * @param string $value
     *
     * @return Identity
     */
    public static function cnpj($value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (!Validator::cnpj()->validator($value)) {
            throw new \InvalidArgumentException("Cnpj $value is invalid");
        }

        $instance = new self();
        $instance->type = 'Cnpj';
        $instance->value = $value;

        return $instance;
    }

    /**
     * Retorna o tipo do documento
     * CPF ou CNPJ
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Retorna o valor do documento
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
