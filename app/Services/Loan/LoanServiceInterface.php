<?php

namespace App\Services\Loan;

use App\Models\Loan;
use App\Models\Repayment;

interface LoanServiceInterface
{
    /**
     * @param  array  $data
     * @return Loan
     *
     * @throws \Exception
     */
    public function create(array $data): Loan;

    /**
     * @param  int  $loanId
     * @param  string  $status
     * @return Loan
     *
     * @throws \Exception
     */
    public function update(int $id, array $data): Loan;

    /**
     * @param  Repayment  $repayment
     * @return Repayment
     *
     * @throws \Exception
     */
    public function makePayment(Repayment $repayment): Repayment;
}
