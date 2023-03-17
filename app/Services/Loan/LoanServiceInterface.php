<?php

namespace App\Services\Loan;

use App\Exceptions\LoanStoreFailException;
use App\Models\Loan;

interface LoanServiceInterface
{
    /**
     * @param  array  $data
     * @return Loan
     *
     * @throws LoanStoreFailException
     */
    public function create(array $data): Loan;
}
