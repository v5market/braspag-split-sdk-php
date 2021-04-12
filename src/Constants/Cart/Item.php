<?php

namespace Braspag\Split\Constants\Cart;

class Item
{
    public const VALUE_YES = 'yes';
    public const VALUE_NO = 'no';
    public const VALUE_OFF = 'off';
    public const VALUE_UNDEFINED = 'undefined';
    public const VALUE_LOW = 'low';
    public const VALUE_NORMAL = 'normal';
    public const VALUE_HIGH = 'high';

    public const VALUES_GIFT_CATEGORIES = [
        self::VALUE_YES,
        self::VALUE_NO,
        self::VALUE_OFF
    ];

    public const VALUES_IMPORTANCE_LEVEL = [
        self::VALUE_LOW,
        self::VALUE_NORMAL,
        self::VALUE_HIGH,
        self::VALUE_OFF,
    ];

    public const TYPES = [
        'adultcontent',
        'coupon',
        'default',
        'eletronicgood',
        'eletronicsoftware',
        'giftcertificate',
        'handlingonly',
        'service',
        'shippingandhandling',
        'shippingonly',
        'subscription',
    ];
}
