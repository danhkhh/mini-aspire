<?php

namespace App\Factories;

use App\Services\Loan\AspireLoanService;
use App\Services\Loan\LoanServiceInterface;

class LoanServiceFactory
{
    /**
     * @return LoanServiceInterface
     */
    public static function create(): LoanServiceInterface
    {
        $default        = config('loan.default_service');

        return match ($default) {
            //'service A' => new ServiceA(),
            //'service B' => new ServiceB(),
            default  => new AspireLoanService(),
        };
    }
}
