<?php

namespace Database\Seeders;

use App\Models\Petugas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PetugasSeeder extends Seeder
{

    public function getCSV()
    {
        $resource = fopen(__DIR__ . '/petugas.csv', 'r');
        if ($resource !== false) {
            $row = 0;
            $header = [];
            $result = [];
            while (($data = fgetcsv($resource, 1024)) !== false) {
                $row++;
                if ($row === 1) $header = $data;
                else $result[] = array_combine($header, $data);
            }
            fclose($resource);
            return $result;
        }
        
        return false;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = $this->getCSV();
        if ($datas) {
            foreach ($datas as $data) {
                $data['tanggal_lahir'] = Carbon::createFromFormat('d/m/Y', $data['tanggal_lahir']);
                if ($petugas = Petugas::whereRaw('lower("nama") like ?', strtolower($data['nama']))->first()) {
                    $petugas->forceFill($data)->save();
                } else {
                    Petugas::create($data);
                }
            }
        }
    }
}
