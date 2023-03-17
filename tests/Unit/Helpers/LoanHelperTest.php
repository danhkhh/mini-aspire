<?php

namespace Tests\Unit\Helpers;

use App\Helpers\LoanHelper;
use PHPUnit\Framework\TestCase;

class LoanHelperTest extends TestCase
{
    /**
     * @group loan
     */
    public function test_create_repayments_amount_from_loan_amount_and_term(): void
    {
        $expected = [3333.33, 3333.33, 3333.34];

        $this->assertEquals($expected, LoanHelper::getRepaymentAmounts(10000, 3));
    }
}
