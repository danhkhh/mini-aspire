<?php

namespace App\Providers;

use App\Factories\LoanServiceFactory;
use App\Services\Loan\LoanServiceInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AspireServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(LoanServiceInterface::class, function () {
            return LoanServiceFactory::create();
        });
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [LoanServiceInterface::class];
    }
}
