<?php

namespace App\Actions;

use Carbon\Carbon;

trait HasDateHelper {

    /**
     * format range
     * @param string|int $month
     * @return string
     */
    public function monthName($month)
    {
        $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $months[(int) $month];
    }

    /**
     * format range
     * @param \DateTimeInterface $date
     * @return string
     */
    public function dateFormat($date)
    {
        $d = $date instanceof Carbon ? $date : Carbon::parse($date);
        return "{$d->format('d')} {$this->monthName($d->format('m'))} {$d->format('Y')}";
    }

    /**
     * format range
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @return string
     */
    public function rangeFormat($start, $end)
    {
        $s = $start instanceof Carbon ? $start : Carbon::parse($start);
        $e = $end instanceof Carbon ? $end : Carbon::parse($end);
        if ($s->isSameDay($e)) {
            return $this->dateFormat($s);
        } elseif ($s->isSameMonth($e)) {
            return "{$s->format('d')}-{$e->format('d')} {$this->monthName($s->format('m'))} {$s->format('Y')}";
        } elseif ($s->isSameYear($e)) {
            return "{$s->format('d')} {$this->monthName($s->format('m'))} - {$e->format('d')} {$this->monthName($e->format('m'))} {$s->format('Y')}";
        } else {
            return "{$this->dateFormat($s)} - {$this->dateFormat($e)}";
        }
    }

    protected function terbilang($x)
    {
        $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

        if ($x < 12)
            return " " . $angka[$x];
        elseif ($x < 20)
            return $this->terbilang($x - 10) . " belas";
        elseif ($x < 100)
            return $this->terbilang($x / 10) . " puluh" . $this->terbilang($x % 10);
        elseif ($x < 200)
            return "seratus" . $this->terbilang($x - 100);
        elseif ($x < 1000)
            return $this->terbilang($x / 100) . " ratus" . $this->terbilang($x % 100);
        elseif ($x < 2000)
            return "seribu" . $this->terbilang($x - 1000);
        elseif ($x < 1000000)
            return $this->terbilang($x / 1000) . " ribu" . $this->terbilang($x % 1000);
        elseif ($x < 1000000000)
            return $this->terbilang($x / 1000000) . " juta" . $this->terbilang($x % 1000000);
    }

    public function dateDuration($start, $end)
    {
        $s = $start instanceof Carbon ? $start : Carbon::parse($start);
        $e = $end instanceof Carbon ? $end : Carbon::parse($end);
        return $s->diffInDays($e) + 1;
    }

    public function dateDurationStr($start, $end)
    {
        $l = $this->dateDuration($start, $end);
        return $l ." (". trim($this->terbilang($l)) .") Hari";
    }

}