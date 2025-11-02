<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = ['merchant_id','amount','settlement_date','reference'];

    public function merchant() {
        return $this->belongsTo(Merchant::class);
    }
}
