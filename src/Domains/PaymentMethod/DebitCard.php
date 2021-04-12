<?php

namespace Braspag\Split\Domains\PaymentMethod;

class DebitCard extends AbstractCard
{
    /**
     * {@inheritDoc}
     */
    public function isCreditCard(): bool
    {
        return false;
    }
}
