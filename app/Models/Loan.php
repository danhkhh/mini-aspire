<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'PENDING';

    const STATUS_APPROVED = 'APPROVED';

    const STATUS_PAID = 'PAID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'term',
        'status',
    ];

    /**
     * @return HasMany
     */
    public function repayments(): HasMany
    {
        return $this->hasMany(Repayment::class);
    }

    /**
     * @return HasMany
     */
    public function waitingPayments(): HasMany
    {
        return $this->repayments()
            ->where('status', self::STATUS_APPROVED)
            ->orderBy('schedule_date', 'ASC');
    }
}
