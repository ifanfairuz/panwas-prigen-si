<?php

namespace App\Models;

use App\Actions\HasDateHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory, HasDateHelper;
    protected $appends = ['tanggal_lahir_formated'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nama',
        'jabatan',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'pendidikan',
        'jk',
        'agama',
        'pangkat',
        'tingkat',
        'nik',
        'nip',
        'npwp',
        'no_hp',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_lahir' => 'date:Y-m-d'
    ];

    /**
     * Determine if the user is an administrator.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function tanggalLahirFormated(): Attribute
    {
        return new Attribute(
            get: fn () => $this->dateFormat($this->tanggal_lahir),
        );
    }
}
