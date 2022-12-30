<?php

namespace App\Http\Controllers;

use App\Actions\HasDateHelper;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    use HasDateHelper;

    public function getMonthsOptions()
    {
        $result = [];
        $start = Carbon::createFromFormat('Y-m', config('app.start_data'));
        while ($start->isBefore(Carbon::now()) || $start->isSameMonth(Carbon::now())) {
            $result[] = [
                'code' => $start->format('Y-m'),
                'label' => $this->monthName($start->format('m')) ." ". $start->format('Y')
            ];
            $start = $start->addMonth();
        }
        return $result;
    }

    public function getDefaultMonth()
    {
        $start_data = Carbon::createFromFormat('Y-m', config('app.start_data'));
        $now = Carbon::now();
        $start = $now->clone()->subMonths(4);
        $start = ($start->isBefore($start_data) ? $start_data : $start)->format('Y-m');
        $end = $now->format('Y-m');
        return ['start' => $start, 'end' => $end];
    }

    /**
     * dashboard page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Dashboard', [
            'months_options' => $this->getMonthsOptions(),
            'default_month' => $this->getDefaultMonth(),
        ]);
    }

    /**
     * get chart surat month
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chart_surat_month(Request $request)
    {
        $start = Carbon::createFromFormat('Y-m', $request->query('start', date('Y-m')))->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $request->query('end', date('Y-m')))->endOfMonth();

        $surat_masuk = SuratMasuk::query()
        ->selectRaw('count("id") as "jumlah", to_char("tanggal", \'YYYY-MM\') as "bulan", "jenis"')
        ->whereRaw('"tanggal" between ? and ?', [$start, $end])
        ->groupByRaw('to_char("tanggal", \'YYYY-MM\'), "jenis"')
        ->orderBy('bulan')
        ->get();
        $surat_keluar = SuratKeluar::query()
        ->selectRaw('count("id") as "jumlah", to_char("tanggal", \'YYYY-MM\') as "bulan", "type" as "jenis"')
        ->whereRaw('"tanggal" between ? and ?', [$start, $end])
        ->groupByRaw('to_char("tanggal", \'YYYY-MM\'), "type"')
        ->orderBy('bulan')
        ->get();
        
        $count_masuk = [];
        $count_keluar = [];
        $jenis_masuk = [];
        $jenis_keluar = [];
        foreach ($surat_masuk as $value) {
            extract($value->toArray(), EXTR_PREFIX_ALL, 'v');
            $count_masuk[$v_bulan] = ($count_masuk[$v_bulan] ?? 0) + $v_jumlah;
            $jenis_masuk[$v_jenis] = $jenis_masuk[$v_jenis] ?? [];
            $jenis_masuk[$v_jenis][$v_bulan] = ($jenis_masuk[$v_jenis][$v_bulan] ?? 0) + $v_jumlah;
        }
        foreach ($surat_keluar as $value) {
            extract($value->toArray(), EXTR_PREFIX_ALL, 'v');
            $count_keluar[$v_bulan] = ($count_keluar[$v_bulan] ?? 0) + $v_jumlah;
            $jenis_keluar[$v_jenis] = $jenis_keluar[$v_jenis] ?? [];
            $jenis_keluar[$v_jenis][$v_bulan] = ($jenis_keluar[$v_jenis][$v_bulan] ?? 0) + $v_jumlah;
        }
        
        $count = ['masuk' => [], 'keluar' => []];
        $jenis = ['masuk' => [], 'keluar' => []];
        $months = $start->diffInMonths($end);
        $jenises_masuk = SuratMasuk::selectRaw('distinct jenis')->get()->pluck('jenis');
        $jenises_keluar = ['tugas_panwas', 'keluar_panwas', 'tugas_sekretariat'];
        for ($m=0; $m <= $months; $m++) { 
            $bulan = $start->clone()->addMonths($m)->format('Y-m');
            $count['masuk'][] = @$count_masuk[$bulan] ?? 0;
            $count['keluar'][] = @$count_keluar[$bulan] ?? 0;
            foreach ($jenises_masuk as $j) {
                $jenis['masuk'][$j][] = (@$jenis_masuk[$j] ?? [])[$bulan] ?? 0;
            }
            foreach ($jenises_keluar as $j) {
                $jenis['keluar'][$j][] = (@$jenis_keluar[$j] ?? [])[$bulan] ?? 0;
            }
        }

        $result = [
            'count' => $count,
            'jenis' => $jenis,
        ];
        return response()->json($result);
    }
}
