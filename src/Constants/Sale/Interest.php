<?php

namespace Braspag\Split\Constants\Sale;

class Interest
{
    public const INTEREST_MERCHANT = 'ByMerchant';
    public const INTEREST_ISSUER = 'ByIssuer';

    public const INTERESTS = [
        self::INTEREST_MERCHANT,
        self::INTEREST_ISSUER,
    ];
}
