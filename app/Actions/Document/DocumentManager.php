<?php

namespace App\Actions\Document;

use App\Actions\HasDateHelper;
use App\Models\Petugas;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentManager
{
    use HasDateHelper;

    /**
     * get petugases
     * @param array $ids
     * @return \Illuminate\Support\Collection<int, \App\Models\Petugas>
     */
    public function getPetugases($ids)
    {
        return count($ids) == 0 ? collect([]) : Petugas::whereIn('id', $ids)->get();
    }

    /**
     * conver docx to pdf
     * @param string $path
     * @param string $outdir ''
     * @return string|null
     */
    public function converDocxToPdf($path, $outdir = '')
    {
        try {
            if (!file_exists($path)) throw new \Exception("File not found");
            if (!$outdir || $outdir == '') {
                $outdir = "generated/" . pathinfo($path, PATHINFO_BASENAME);
                Storage::mkdir($outdir);
                $outdir = Storage::path($outdir);
            }
            $command = "libreoffice --headless --convert-to pdf ${$path} --outdir ${$outdir}";
            $result = shell_exec("type libreoffice &> /dev/null && { ${$command} &> /dev/null; echo \"done\"; exit 0; }  || { exit 1; }");
            if ($result == "done") return join(DIRECTORY_SEPARATOR, [$outdir, pathinfo($path, PATHINFO_BASENAME) . ".pdf"]);
            throw new \Exception("Error");
        } catch (\Exception $e) {
            return null;
        }
    }

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
            if (!file_exists($template)) throw new \Exception("Template not found.");
            $filename = "surat_keluar/{$surat->type}_{$dest}";
            $dest = Storage::path($filename);

            $processor = new TemplateProcessor($template);
            $processor->setValues([
                'nomor' => $surat->nomor,
                'perihal' => $surat->perihal,
                'tempat' => $surat->tempat,
                'nomor_surat_masuk' => $surat->surat_masuk ? $surat->surat_masuk->nomor : '-',
                'tanggal_dinas' => $surat->tanggal_dinas_readable(),
                'tanggal_pembuatan' => $this->dateFormat($surat->tanggal),
            ]);
            $petugases = $surat->petugases->count() == 0 ? $this->getPetugases($surat->petugases_id) : $surat->petugases;
            $petugases = $petugases->map(fn (Petugas $p, int $i) => [
                ...$p->only(['nama', 'jabatan']),
                ...($i == 0 ? ['petugas' => 'Kepada', 'd' => ':'] : ['petugas' => '', 'd' => '']),
            ])->toArray();
            $processor->cloneRowAndSetValues('petugas', $petugases);

            if (file_exists($dest)) unlink($dest);
            $processor->saveAs($dest);
            return $filename;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }
}