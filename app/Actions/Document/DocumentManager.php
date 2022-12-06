<?php

namespace App\Actions\Document;

use App\Actions\HasDateHelper;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentManager
{
    use HasDateHelper;

    /**
     * generate surat keluar
     * @param \App\Models\SuratKeluar $surat
     * @param string $dest
     * @return string|false
     */
    public function generateSuratKeluar($surat, $dest)
    {
        try {
            $template = Storage::path("templates/{$surat->type}.docx");
            if (!file_exists($template)) return false;
            $filename = "surat_keluar/{$surat->type}_{$dest}";
            $dest = Storage::path($filename);

            $processor = new TemplateProcessor($template);
            $processor->setValues([
                'nomor' => $surat->nomor,
                'perihal' => $surat->perihal,
                'tempat' => $surat->tempat,
                'nomor_surat_masuk' => $surat->surat_masuk ? $surat->surat_masuk->nomor : '-',
                'nama_petugas' => $surat->petugas->nama,
                'jabatan_petugas' => $surat->petugas->jabatan,
                'tanggal_dinas' => $surat->tanggal_dinas_readable(),
                'tanggal_pembuatan' => $this->dateFormat($surat->tanggal),
            ]);
            
            if (file_exists($dest)) unlink($dest);
            $processor->saveAs($dest);
            return $filename;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }
    
}
