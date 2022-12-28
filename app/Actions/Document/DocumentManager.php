<?php

namespace App\Actions\Document;

use App\Actions\HasDateHelper;
use App\Models\Petugas;
use Illuminate\Support\Facades\Log;
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
        return count($ids ?? []) == 0 ? collect([]) : Petugas::whereIn('id', $ids)->get();
    }

    /**
     * conver docx to pdf
     * @param string $path
     * @param string $outdir ''
     * @return string|null
     */
    public function convertDocxToPdf($path, $outdir = '')
    {
        try {
            if (!Storage::exists($path)) throw new \Exception("File not found");
            if (!$outdir || $outdir == '') {
                $outdir = "generated/" . pathinfo($path, PATHINFO_FILENAME);
                Storage::makeDirectory($outdir);
            }
            $path = Storage::path($path);
            $outdir_abs = Storage::path($outdir);
            $command = "libreoffice --headless --convert-to pdf ${path} --outdir ${outdir_abs}";
            $command = "type libreoffice &> /dev/null && { ${command} && echo \"DONE\"; }  || { exit 1; }";
            $result = shell_exec($command);
            if ($result && strpos("DONE", $result)) return join(DIRECTORY_SEPARATOR, [$outdir, pathinfo($path, PATHINFO_FILENAME) . ".pdf"]);
            throw new \Exception("Error - ${command}");
        } catch (\Exception $e) {
            Log::error($e);
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
                'tanggal_dinas' => $surat->tanggal_dinas_readable(),
                'tanggal_pembuatan' => $this->dateFormat($surat->tanggal),
            ]);
            
            $extras = [];
            if ($surat->surat_masuk) {
                $extras[] = [
                    'jenis_extra' => $surat->surat_masuk->jenis ?? 'Undangan',
                    'nomor_extra' => $surat->surat_masuk->nomor,
                ];
            }
            $processor->cloneBlock('extra_info', 0, true, false, $extras);

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

    /**
     * generate surat perjalanan dinas
     * @param \App\Models\SuratKeluar $surat
     * @param string $dest
     * @return string|false
     */
    public function generateSPD($surat, $dest)
    {
        try {
            $template = Storage::path("templates/spd.docx");
            if (!file_exists($template)) throw new \Exception("Template not found.");
            $filename = "surat_keluar/spd_{$dest}";
            $dest = Storage::path($filename);

            $processor = new TemplateProcessor($template);
            $general_datas = [
                'perihal' => $surat->perihal,
                'tempat' => $surat->tempat,
                'tanggal_dinas_start' => $this->dateFormat($surat->tanggal_dinas_start),
                'tanggal_dinas_end' => $this->dateFormat($surat->tanggal_dinas_end),
                'tanggal_dinas_length' => $surat->duration_dinas,
                'tanggal' => $this->dateFormat($surat->tanggal),
            ];
            $petugases = $this->getPetugases($surat->petugases_id);
            $datas = $petugases->map(fn (Petugas $p) => [
                ...$general_datas,
                'nama' => $p->nama,
                'pangkat' => $p->pangkat,
                'jabatan' => $p->jabatan,
                'tingkat' => $p->tingkat,
            ])->toArray();
            $processor->cloneBlock('spd_block', 0, true, false, $datas);
            $processor->setValues($general_datas);

            if (file_exists($dest)) unlink($dest);
            $processor->saveAs($dest);
            return $filename;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }
}
