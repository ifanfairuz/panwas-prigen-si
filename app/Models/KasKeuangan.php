<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasKeuangan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'saldo',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @return \App\Models\KasKeuangan
     */
    public static function main()
    {
        return static::first();
    }
}
