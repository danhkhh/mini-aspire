<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Http\Requests\UpdateStatusLoanRequest;
use App\Http\Resources\LoanResource;
use App\Models\Loan;
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
     *
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

    /**
     * View a loan.
     *
     * @return JsonResponse
     */
    public function view(Loan $loan): JsonResponse
    {
        $loan->load('repayments');

        return $this->response->respond(new LoanResource($loan));
    }

    /**
     * @param  int  $id
     * @param  UpdateStatusLoanRequest  $request
     * @return JsonResponse
     */
    public function updateStatus(int $id, UpdateStatusLoanRequest $request): JsonResponse
    {
        try {
            $this->loanService->update($id, $request->only('status'));

            return $this->response
                ->setStatusCode(Response::HTTP_NO_CONTENT)
                ->respond(null);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->respond($e->getMessage());
        }
    }
}
