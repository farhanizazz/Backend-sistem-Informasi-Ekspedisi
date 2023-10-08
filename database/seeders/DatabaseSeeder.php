<?php

namespace Database\Seeders;

use App\Models\Master\ArmadaModel;
use App\Models\Master\PenyewaModel;
use App\Models\Master\RekeningModel;
use App\Models\Master\RoleModel;
use App\Models\Master\SopirModel;
use App\Models\Master\SubkonModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        RoleModel::factory(10)->create();
        User::factory(10)->create();
        PenyewaModel::create([
            'nama_perusahaan' =>  'PT. Sinar Jaya',
            'alamat' => 'Jl. Raya Cikarang',
            'penanggung_jawab' => 'Budi',
            'contact_person' => '08123456789',
            'keterangan' => 'Perusahaan yang bergerak di bidang transportasi'
        ]);
        ArmadaModel::create([
            'nopol' => 'B 1234 ABC',
            'merk' => 'Hino',
            'jenis' => 'Truk',
            'tgl_stnk' => '2021-10-05',
            'tgl_uji_kir' => '2021-10-05',
            'status_stnk' => 'aktif',
            'status_uji_kir' => 'aktif',
            'keterangan' => 'Truk yang digunakan untuk angkutan barang'
        ]);
        SopirModel::create([
            'nama' => 'Budi',
            'alamat' => 'Jl. Raya Cikarang',
            'ktp'   => '1234567890',
            'sim'   => '1234567890',
            'nomor_hp' => '08123456789',
            'keterangan' => 'Sopir yang berpengalaman',
            'tanggal_gabung' => '2021-10-05'
        ]);
        RekeningModel::create([
            'nama' => 'Ganti rugi',
            'nominal' => -10000
        ]);
        RekeningModel::create([
            'nama' => 'Ban Bocor',
            'nominal' => -50000
        ]);
        SubkonModel::create([
            'nama_perusahaan' => 'PT. Subkon',
            'alamat' => 'Jl. Raya Cikarang',
            'penanggung_jawab' => 'Budi',
            'ket'   => 'Perusahaan yang bergerak di bidang transportasi',
        ]);
    }
}
