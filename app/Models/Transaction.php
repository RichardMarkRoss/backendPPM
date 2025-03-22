<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'debit_card_id',
        'amount',
        'type',
        'status'
    ];

    public function debitCard()
    {
        return $this->belongsTo(DebitCard::class);
    }
}