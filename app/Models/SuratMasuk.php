<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;
    protected $table = "surat_masuk";

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date:Y-m-d'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nomor',
        'tanggal',
        'dari',
        'alamat',
        'perihal',
        'tempat',
        'keterangan',
        'doc',
        'jenis',
    ];
}
