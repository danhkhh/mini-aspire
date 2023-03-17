<?php

namespace App\Helpers;

/**
 * Class LoanHelper
 *
 * @package App\Helpers
 */
class LoanHelper
{
    /**
     * @param  int  $amount
     * @param  int  $term
     * @return array
     */
    public static function getRepaymentAmounts(int $amount, int $term): array
    {
        /*
         * Not all repayments are the same.
         * Eg: amount = 10000, term = 3 => repayment1 = 3333.33, repayment2 = 3333.33,
         * last repayment = amount - SUM(previous repayments) = 3333.34
         */
        $result   = array_fill(0, $term - 1, round($amount / $term, 2));
        $result[] = $amount - array_sum($result);

        return $result;
    }
}
