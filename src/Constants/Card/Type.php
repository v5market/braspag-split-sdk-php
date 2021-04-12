<?php

namespace Braspag\Split\Constants\Card;

class Type
{
    public const CREDIT = 'CreditCard';
    public const DEBIT = 'DebitCard';

    public const ALL = [
        'SplittedCreditCard',
        'SplittedDebitCard',
        self::CREDIT,
        self::DEBIT,
    ];

    /**
     * Verifica se um tipo de cartão existe (Débito ou Crédito)
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
     * Captura o nome correto do tipo de cartão
     * Evita problema de _case sensitive_
     *
     * @param string $value
     *
     * @return string Retorna o nome correto do tipo de cartão
     */
    public static function get(string $value)
    {
        $filtered = array_filter(self::ALL, function ($brand) use ($value) {
            $newValue = trim(strtolower($value));
            $brand = strtolower($brand);

            return $brand === $newValue
                || substr($brand, 0, -4) === $newValue;
        });

        return count($filtered) > 0 ? reset($filtered) : null;
    }
}
