<?php

namespace Braspag\Split\Domains\PaymentMethod;

class CreditCard extends AbstractCard
{
    /**
     * {@inheritDoc}
     */
    public function isCreditCard(): bool
    {
        return true;
    }
}
