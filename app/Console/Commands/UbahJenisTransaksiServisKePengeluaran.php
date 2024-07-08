<?php

namespace App\Console\Commands;

use App\Enums\JenisTransaksiMutasiEnum;
use App\Models\Master\MutasiModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UbahJenisTransaksiServisKePengeluaran extends Command
{
    private $mutasiModel;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jenis_transaksi_servis:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ubah jenis transaksi servis ke pengeluaran';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->mutasiModel = new MutasiModel();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->mutasiModel->where('asal_transaksi', 'servis')->update([
                'jenis_transaksi' => JenisTransaksiMutasiEnum::PENGELUARAN->value
            ]);
            DB::commit();
            $this->info('Berhasil mengubah jenis transaksi servis ke pengeluaran');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error('Gagal mengubah jenis transaksi servis ke pengeluaran');
        }
    }
}
