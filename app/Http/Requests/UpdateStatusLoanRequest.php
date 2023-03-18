<?php

namespace App\Http\Requests;

use App\Models\Loan;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                Rule::exists('loans')->where(function (Builder $query) {
                    return $query->where('status', Loan::STATUS_PENDING);
                }),
            ],
            'status' => [
                'required',
                Rule::in([Loan::STATUS_APPROVED]), // in the future, there is probably DECLINED as well
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'id' => $this->id,
        ]);
    }
}
