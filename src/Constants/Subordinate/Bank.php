<?php

namespace Braspag\Split\Constants\Subordinate;

class Bank
{
    public const ACCOUNT_TYPE_CHECKING = 'CheckingAccount';
    public const ACCOUNT_TYPE_SAVINGS = 'SavingsAccount';

    public const ACCOUNT_TYPES = [
        self::ACCOUNT_TYPE_CHECKING,
        self::ACCOUNT_TYPE_SAVINGS
    ];
}
