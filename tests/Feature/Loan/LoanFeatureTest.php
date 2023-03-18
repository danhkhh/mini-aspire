<?php

namespace Tests\Feature\Loan;

use App\Models\Loan;
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
     * @dataProvider getValidParams
     *
     * @group loan
     */
    public function test_unauthenticated_users_can_not_create_loan(array $params): void
    {
        $response = $this->postJson(route('loans.store'), $params);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @dataProvider getInvalidParams
     *
     * @group loan
     */
    public function test_customer_can_not_create_loan_with_invalid_params(array $params): void
    {
        $this->signInById(UserData::CUSTOMER_ID);
        $response = $this->postJson(route('loans.store'), $params);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @dataProvider getValidParams
     *
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
            'amount'  => $params['amount'],
            'term'    => $params['term'],
            'status'  => Loan::STATUS_PENDING,
        ]);

        // repayments exist
        $this->assertDatabaseCount('repayments', $params['term']);
    }

    /**
     * @group loan
     */
    public function test_unauthenticated_user_can_not_update_loan_status(): void
    {
        $response = $this->postJson(route('loans.update_status', [1]), ['status' => Loan::STATUS_APPROVED]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @group loan
     */
    public function test_customer_can_not_update_loan_status(): void
    {
        $loan = $this->createPendingLoanAndRepayment();
        $this->signInById(UserData::CUSTOMER_ID);
        $response = $this->postJson(route('loans.update_status', [$loan->id]), ['status' => Loan::STATUS_APPROVED]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @group loan
     */
    public function test_admin_can_not_update_loan_status_with_invalid_id(): void
    {
        $this->signInById(UserData::ADMIN_ID);
        $response = $this->postJson(route('loans.update_status', [-1]), ['status' => Loan::STATUS_APPROVED]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @group loan
     */
    public function test_admin_can_not_update_loan_status_already_approved(): void
    {
        $loan = Loan::factory()->approved()->create(['user_id' => UserData::ADMIN_ID]);
        $this->signInById(UserData::ADMIN_ID);
        $response = $this->postJson(route('loans.update_status', [$loan->id]), ['status' => Loan::STATUS_APPROVED]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @group loan
     */
    public function test_admin_can_update_pending_loan_to_approved(): void
    {
        $loan = $this->createPendingLoanAndRepayment();
        $this->signInById(UserData::ADMIN_ID);
        $response = $this->postJson(route('loans.update_status', [$loan->id]), ['status' => Loan::STATUS_APPROVED]);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @group loan
     */
    public function test_admin_can_view_loan(): void
    {
        $loan = $this->createPendingLoanAndRepayment();
        $this->signInById(UserData::ADMIN_ID);
        $response = $this->getJson(route('loans.view', ['loan' => $loan]));
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @group loan
     */
    public function test_unauthenticated_user_can_not_view_loan(): void
    {
        $loan     = $this->createPendingLoanAndRepayment();
        $response = $this->getJson(route('loans.view', ['loan' => $loan]));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @group loan
     */
    public function test_customer_can_not_view_other_loan(): void
    {
        $loan = $this->createPendingLoanAndRepayment();
        $this->signInById(UserData::CUSTOMER2_ID);
        $response = $this->getJson(route('loans.view', ['loan' => $loan]));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @group loan
     */
    public function test_customer_can_view_own_loan(): void
    {
        $loan = $this->createPendingLoanAndRepayment();
        $this->signInById(UserData::CUSTOMER_ID);
        $response = $this->getJson(route('loans.view', ['loan' => $loan]));
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return Loan
     */
    private function createPendingLoanAndRepayment(): Loan
    {
        $loan = Loan::factory()->pending()->create(['user_id' => UserData::CUSTOMER_ID]);
        // @todo: repayments
        return $loan;
    }
}
