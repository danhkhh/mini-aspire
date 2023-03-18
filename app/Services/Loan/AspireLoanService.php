<?php

namespace App\Services\Loan;

use App\Exceptions\LoanStoreFailException;
use App\Exceptions\LoanUpdateFailException;
use App\Exceptions\MakePaymentFailException;
use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AspireLoanService implements LoanServiceInterface
{
    /**
     * @param  array  $data
     * @return Loan
     *
     * @throws LoanStoreFailException
     */
    public function create(array $data): Loan
    {
        try {
            DB::beginTransaction();
            $loan = new Loan($data);
            Auth::user()->loans()->save($loan);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            throw new LoanStoreFailException();
        }
        DB::commit();

        return $loan;
    }

    /**
     * @param  int  $loanId
     * @param  string  $status
     * @return Loan
     *
     * @throws LoanUpdateFailException
     */
    public function update(int $id, array $data): Loan
    {
        try {
            DB::beginTransaction();
            $loan = Loan::findOrFail($id);
            $loan->update($data);

            $loan->repayments()->update(['status' => $data['status']]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            throw new LoanUpdateFailException();
        }
        DB::commit();

        return $loan;
    }

    /**
     * @param  Repayment  $repayment
     * @return Repayment
     *
     * @throws MakePaymentFailException
     */
    public function makePayment(Repayment $repayment): Repayment
    {
        try {
            DB::beginTransaction();
            $repayment->update(['status' => Loan::STATUS_PAID]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            throw new MakePaymentFailException;
        }
        DB::commit();

        return $repayment;
    }
}
