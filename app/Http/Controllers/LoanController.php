<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeRepaymentRequest;
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
     * Customer create a new loan
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
     * Admin/Customer views a loan.
     *
     * @return JsonResponse
     */
    public function view(Loan $loan): JsonResponse
    {
        $loan->load('repayments');

        return $this->response->respond(new LoanResource($loan));
    }

    /**
     * Admin approves the loan (Consider that Admin can decline loan in the future)
     *
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

    /**
     * Customer makes a repayment
     *
     * @param  MakeRepaymentRequest  $request
     * @param  Loan  $loan
     * @return JsonResponse
     */
    public function makePayment(MakeRepaymentRequest $request, Loan $loan): JsonResponse
    {
        try {
            $this->loanService->makePayment($request->nextRepayment);

            return $this->response
                ->setStatusCode(Response::HTTP_NO_CONTENT)
                ->respond(null);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->respond($e->getMessage());
        }
    }
}
