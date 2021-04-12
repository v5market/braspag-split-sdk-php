<?php

namespace Braspag\Split\Constants\Card;

class Brand
{
    public const VISA = 'Visa';
    public const MASTER = 'Master';
    public const AMEX = 'Amex';
    public const ELO = 'Elo';
    public const DINERS = 'Diners';
    public const DISCOVER = 'Discover';
    public const HIPERCARD = 'Hipercard';
    public const MAESTRO = 'Maestro';
    public const AURA = 'Aura';
    public const JCB = 'JCB';

    public const ALL = [
        self::VISA,
        self::MASTER,
        self::AMEX,
        self::ELO,
        self::DINERS,
        self::DISCOVER,
        self::HIPERCARD,
        self::MAESTRO,
        self::AURA,
        self::JCB,
    ];

    /**
     * Verifica se uma bandeira existe
     *
     * @param string $value
     *
     * @return bool Retorna verdadeiro, caso exista.
     */
    public static function exist(string $value)
    {
        $types = array_map('strtolower', self::ALL);
        return in_array(strtolower($value), $types);
    }

    /**
     * Captura o nome correto de uma bandeira
     * Evita problema de _case sensitive_
     *
     * @param string $value
     *
     * @return string Retorna o nome correto da bandeira
     */
    public static function get(string $value)
    {
        $filtered = array_filter(self::ALL, function ($brand) use ($value) {
            return strtolower($brand) === trim(strtolower($value));
        });

        return count($filtered) > 0 ? reset($filtered) : null;
    }
}
