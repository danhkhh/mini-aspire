<?php

namespace Tests\Feature\Loan;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\TestData\LoanData;
use Tests\TestData\UserData;

class LoanFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array
     */
    public static function getInvalidParams(): array
    {
        return (new LoanData)->invalidLoanParams;
    }

    /**
     * @return array
     */
    public static function getValidParams(): array
    {
        return (new LoanData)->validLoanParams;
    }

    /**
     *
     * @dataProvider getValidParams
     * @group loan
     */
    public function test_unauthenticated_users_can_not_create_loan(array $params): void
    {
        $response = $this->postJson(route('loans.store'), $params);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     *
     * @dataProvider getInvalidParams
     * @group loan
     */
    public function test_customer_can_not_create_loan_with_invalid_params(array $params): void
    {
        $this->signInById(UserData::CUSTOMER_ID);
        $response = $this->postJson(route('loans.store'), $params);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     *
     * @dataProvider getValidParams
     * @group loan
     */
    public function test_customer_can_create_loan_with_valid_params(array $params): void
    {
        $this->signInById(UserData::CUSTOMER_ID);
        $response = $this->postJson(route('loans.store'), $params);
        $response->assertStatus(Response::HTTP_CREATED);
        // loan exists
        $this->assertDatabaseHas('loans', [
           'user_id' => UserData::CUSTOMER_ID,
           'amount' => $params['amount'],
           'term' => $params['term'],
           'status' => Loan::STATUS_PENDING,
        ]);

        // repayments exist
        $this->assertDatabaseCount('repayments', $params['term']);
    }
}
