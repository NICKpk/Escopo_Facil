<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PixPayment extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $attributes = ['status' => 'pending'];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime', 'paid_at' => 'datetime', 'amount' => 'integer'];
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
