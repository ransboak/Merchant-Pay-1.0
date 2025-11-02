<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['merchant_id','amount', 'fee','payment_reference','status'];

    public function merchant() {
        return $this->belongsTo(Merchant::class);
    }
}
