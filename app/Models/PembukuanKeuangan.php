<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembukuanKeuangan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'label',
        'amount',
        'qty',
        'total',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $amount = $model->amount ?? 0;
            $qty = $model->qty ?? 0.0;
            $model->total = $amount * $qty;
        });
    }
}
