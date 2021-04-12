<?php

namespace Braspag\Split\Constants\Subordinate;

class Attachment
{
    public const TYPE_PROOF_OF_BANK_DOMICILE = 'ProofOfBankDomicile';
    public const TYPE_MODEL_OF_ADHESION_TERM = 'ModelOfAdhesionTerm';

    public const TYPES = [
        self::TYPE_PROOF_OF_BANK_DOMICILE,
        self::TYPE_MODEL_OF_ADHESION_TERM
    ];

    public const ALLOWED_EXTENSIONS = [
        'pdf',
        'png',
        'jpg',
        'jpeg'
    ];
}
