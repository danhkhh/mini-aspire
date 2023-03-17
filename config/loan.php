<?php
$mysqlBigUnsignedMaxValue = 18446744073709551615;
$mysqlSmallUnsignedMaxValue = 65535;

return [
    'default_service' => env('LOAN_SERVICE', 'aspire'),
    'loan_max_amount' => min($mysqlBigUnsignedMaxValue, env('LOAN_MAX_AMOUNT', $mysqlBigUnsignedMaxValue)),
    'loan_min_amount' => max(1, env('LOAN_MIN_AMOUNT', 1)),
    'loan_max_term' => min($mysqlSmallUnsignedMaxValue, env('LOAN_MAX_TERM', $mysqlSmallUnsignedMaxValue)),
    'loan_min_term' => max(2, env('LOAN_MAX_TERM', 2)),
    'statuses' => [
        \App\Models\Loan::STATUS_PENDING,
        \App\Models\Loan::STATUS_APPROVED,
        \App\Models\Loan::STATUS_PAID,
        ],
];
