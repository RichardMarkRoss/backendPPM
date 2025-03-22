<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'term',
        'remaining_balance',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scheduledRepayments()
    {
        return $this->hasMany(ScheduledRepayment::class);
    }

    public function receivedRepayments()
    {
        return $this->hasMany(ReceivedRepayment::class);
    }
}