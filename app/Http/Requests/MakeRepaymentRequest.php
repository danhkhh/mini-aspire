<?php

namespace App\Http\Requests;

use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MakeRepaymentRequest extends FormRequest
{
    public ?Repayment $nextRepayment;

    /**
     * Determine if user can make payment for the given loan:
     *  - loan gets approved
     *  - there is at least 1 pending repayment
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->loan->status == Loan::STATUS_APPROVED && $this->loan->waitingPayments()->exists();
    }

    public function prepareForValidation()
    {
        $this->nextRepayment = $this->loan->waitingPayments()->first();
        $this->merge([
            'loan_id' => $this->loan->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'loan_id' => [
                'required',
                Rule::in([$this->nextRepayment?->loan_id]),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:'.$this->nextRepayment?->amount,
                'max:'.config('loan.loan_max_amount'),
            ],
        ];
    }
}
