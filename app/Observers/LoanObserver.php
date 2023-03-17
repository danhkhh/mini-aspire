<?php

namespace App\Observers;

use App\Helpers\LoanHelper;
use App\Models\Loan;
use App\Models\Repayment;
use App\Services\Loan\LoanServiceInterface;
use Carbon\Carbon;

class LoanObserver
{
    /**
     *
     * @param Loan $loan
     */
    public function created(Loan $loan): void
    {
        $date             = Carbon::now();
        $amountRepayments = LoanHelper::getRepaymentAmounts($loan->amount, $loan->term);
        $repayments       = collect($amountRepayments)->map(function ($amount) use ($date) {
            $date->addWeek();

            return new Repayment([
                'amount'        => $amount,
                'schedule_date' => $date,
            ]);
        });
        $loan->repayments()->saveMany($repayments);
    }
}
