<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = ['name','email','business_name','account_number','bank_name','is_active'];

    public function wallet() {
        return $this->hasOne(Wallet::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function settlements() {
        return $this->hasMany(Settlement::class);
    }
}
