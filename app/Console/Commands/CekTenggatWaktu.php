<?php

namespace App\Console\Commands;

use App\Helpers\Master\ArmadaHelper;
use Illuminate\Console\Command;

class CekTenggatWaktu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cek:tenggat_waktu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk mengecek tenggat waktu uji kir dan stnk kendaraan';

    /**
     * Create a new command instance.
     *
     * @return void
     */

     protected $armadaHelper;
    public function __construct()
    {
        parent::__construct();
        $this->armadaHelper = new ArmadaHelper();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = $this->armadaHelper->getAll();
        foreach ($data as $key => $value) {
            $this->armadaHelper->cekTanggalBerlaku($value);
        }
        log("Cek tenggat waktu uji kir dan stnk kendaraan");
    }
}
