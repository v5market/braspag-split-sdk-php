<?php

namespace Braspag\Split\Constants\FraudAnalysis;

class FraudAnalysis
{
    public const SEQUENCE_ANALYSE = 'AnalyseFirst';
    public const SEQUENCE_AUTHORIZE = 'AuthorizeFirst';

    public const SEQUENCES = [
        self::SEQUENCE_ANALYSE,
        self::SEQUENCE_AUTHORIZE
    ];

    public const ORG_ID_SANDBOX = '1snn5n9w';
    public const ORG_ID_PRODUCTION = 'k8vif92e';

    public const SHIPPING_METHODS = [
        'sameday',
        'oneday',
        'twoday',
        'threeday',
        'lowcost',
        'pickup',
        'other',
        'none',
    ];

    public const STATUSES = [
        'Unknown',
        'Accept',
        'Reject',
        'Review',
        'Aborted',
        'Unfinished'
    ];
}
