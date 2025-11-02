<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['merchant_id', 'balance'];

    public function merchant() {
        return $this->belongsTo(Merchant::class);
    }
}
