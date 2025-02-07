<?php

if (!function_exists('format_date')) {
  function format_date($date)
  {
    return Carbon\Carbon::parse($date)
      ->locale('id')
      ->translatedFormat('d F Y');
  }
}

if (!function_exists('rupiah')) {
  function rupiah($angka)
  {
    $hasil_rupiah = 'Rp ' . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
  }
}
