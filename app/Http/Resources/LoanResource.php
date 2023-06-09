<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'amount' => $this->amount,
            'term'   => $this->term,
            'status' => $this->status,

            // avoid N+1 query issues
            'repayments' => RepaymentResource::collection($this->whenLoaded('repayments')),
        ];
    }
}
