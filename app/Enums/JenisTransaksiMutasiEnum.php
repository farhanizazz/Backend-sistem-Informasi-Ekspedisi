<?php
namespace App\Enums;

enum JenisTransaksiMutasiEnum: string
{
    case ORDER = "order";
    case JUAL = 'jual';
    case UANG_JALAN = 'uang_jalan';
    case PENGELUARAN = 'pengeluaran';
    case PEMASUKAN = 'pemasukan';
}