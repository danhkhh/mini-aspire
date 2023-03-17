<?php

namespace Tests\TestData;

class LoanData
{
    public array $validLoanParams;

    public array $invalidLoanParams;

    public function __construct()
    {
        $this->validLoanParams = [
            [
                [
                    'amount' => 100,
                    'term'   => 3,
                ],
            ],
        ];
        $this->invalidLoanParams = [
            'amount_gt_max' => [
                [
                    'amount' => 18446744073709551615 + 1,
                    'term'   => 3,
                ],
            ],
            'amount_lt_min' => [
                [
                    'amount' => 0,
                    'term'   => 3,
                ],
            ],
            'float_amount' => [
                [
                    'amount' => 100.12,
                    'term'   => 3,
                ],
            ],
            'float_term' => [
                [
                    'amount' => 100,
                    'term'   => 3.5,
                ],
            ],
            'term_gt_max' => [
                [
                    'amount' => 100,
                    'term'   => 65535 + 1,
                ],
            ],
            'term_lt_min' => [
                [
                    'amount' => 100,
                    'term'   => 1,
                ],
            ],
        ];
    }
}
