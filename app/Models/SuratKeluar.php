<?php

namespace App\Models;

use App\Actions\HasDateHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory, HasDateHelper;
    protected $table = "surat_keluar";
    protected $appends = ['range_dinas'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'tanggal_dinas_start' => 'date:Y-m-d',
        'tanggal_dinas_end' => 'date:Y-m-d',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'petugas_id',
        'surat_masuk_id',
        'type',
        'nomor',
        'tanggal',
        'tanggal_dinas_start',
        'tanggal_dinas_end',
        'tujuan',
        'alamat',
        'perihal',
        'tempat',
        'keterangan',
        'doc'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (!$model->surat_masuk_id || $model->surat_masuk_id == '') $model->surat_masuk_id = 0; 
            if (!$model->petugas_id || $model->petugas_id == '') $model->petugas_id = 0; 
            if (
                $model->tanggal_dinas_start &&
                (!$model->tanggal_dinas_end || $model->tanggal_dinas_end == '')
            ) $model->tanggal_dinas_end = $model->tanggal_dinas_start; 
        });
    }

    /**
     * get next nomor urut
     * @return string
     */
    public static function getNextUrut()
    {
        try {
            $data = SuratKeluar::selectRaw('MAX(SUBSTRING("nomor", 1, 3)) as "urut"')->firstOrFail();
            $urut = (int) (@$data['urut'] ?? '0');
            return str_pad(++$urut, 3, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * get tanggal dinas readable
     * @return string
     */
    public function tanggal_dinas_readable()
    {
        try {
            return $this->rangeFormat($this->tanggal_dinas_start, $this->tanggal_dinas_end);
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Determine if the user is an administrator.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function rangeDinas(): Attribute
    {
        return new Attribute(
            get: fn () => $this->tanggal_dinas_readable(),
        );
    }

    public function surat_masuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }
}
