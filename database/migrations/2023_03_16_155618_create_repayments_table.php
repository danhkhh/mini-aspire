<?php

use App\Models\Loan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Loan::class)
                ->nullable()
                ->constrained()
                ->onUpdate('SET NULL')
                ->onDelete('SET NULL');
            $table->date('schedule_date');
            $table->unsignedFloat('amount', 22);
            $table->enum('status', config('loan.statuses'))->default(Loan::STATUS_PENDING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
