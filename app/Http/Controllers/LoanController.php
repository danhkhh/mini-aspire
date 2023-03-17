<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Http\Resources\LoanResource;
use App\Services\Loan\LoanServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LoanController extends Controller
{
    /**
     * LoanController constructor.
     *
     * @param  LoanServiceInterface  $loanService
     */
    public function __construct(private LoanServiceInterface $loanService)
    {
        parent::__construct();
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @param  StoreLoanRequest  $request
     * @return JsonResponse
     */
    public function store(StoreLoanRequest $request): JsonResponse
    {
        try {
            return $this->response
                ->setStatusCode(Response::HTTP_CREATED)
                ->respond(new LoanResource($this->loanService->create($request->validated())));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->respond($e->getMessage());
        }
    }
}
