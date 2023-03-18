<?php

namespace App\Observers;

use App\Models\Loan;
use App\Models\Repayment;

class RepaymentObserver
{
    /**
     * Handle the Repayment "updated" event.
     */
    public function updated(Repayment $repayment): void
    {
        if ($repayment->isDirty('status') && $repayment->status == Loan::STATUS_PAID) {
            $loan = $repayment->loan;
            if (! $loan->repayments()->where('status', '!=', Loan::STATUS_PAID)->count()) {
                // all repayments are already paid, the loan become PAID
                $loan->update(['status' => Loan::STATUS_PAID]);
            }
        }
    }
}
