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
        $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'];
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

}