<?php

namespace App\Http\Requests\OrderRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public $validator = null;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request = $this->request->all();
        if (!isset($request['status_kendaraan'])) {
            return [
                'status_kendaraan' => 'required|in:Sendiri,Subkon',
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required_if:status_kendaraan_sendiri,Kontrak',
                'status_kendaraan' => 'required|in:Sendiri,Subkon',
                'status_surat_jalan' => 'required|in:Sopir,Kantor,Selesai',
                'm_penyewa_id' => 'required|exists:master_penyewa,id',
                'muatan' => 'required',
                'm_armada_id' => 'required|exists:master_armada,id',
                'm_sopir_id' => 'required|exists:master_sopir,id',
                'asal' => 'required',
                'tujuan' => 'required',
                'harga_order' => 'required|numeric',
                'status_harga_order' => 'required|in:Pelunasan,Dp',
                'biaya_lain_harga_order' => 'array',
                'status_pajak' => 'required|in:ya,tidak',
                'setor' => 'required_if:status_kendaraan,Sendiri',
                'uang_jalan' => 'required_if:status_kendaraan,Sendiri',
                'potongan_wajib' => 'required_if:status_kendaraaan,Sendiri',
                'biaya_lain_uang_jalan' => 'array',
                'harga_jual' => 'required_if:status_kendaraan,Subkon',
                'status_harga_jual' => 'required_if:status_kendaraan,Subkon|in:Pelunasan,Dp',
                'biaya_lain_harga_jual' => 'array'
            ];
        }
        switch ($request['status_kendaraan']) {
            case 'Sendiri':
                $rule = [
                     'status_kendaraan_sendiri' => 'required_if:status_kendaraaan,Sendiri|in:Berangkat,Pulang,Kontrak,Kota-Kota',
                ];
                break;
            default:
                $rule = [
                    'm_subkon_id' => 'required_if:status_kendaraan,Subkon|exists:master_subkon,id',
                ];
                break;
        }
        return array_merge([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required_if:status_kendaraan_sendiri,Kontrak',
            'status_kendaraan' => 'required|in:Sendiri,Subkon',
            'status_surat_jalan' => 'required|in:Sopir,Kantor,Selesai',
            'm_penyewa_id' => 'required|exists:master_penyewa,id',
            'muatan' => 'required',
            'm_armada_id' => 'required|exists:master_armada,id',
            'm_sopir_id' => 'required|exists:master_sopir,id',
            'asal' => 'required',
            'tujuan' => 'required',
            'harga_order' => 'required|numeric',
            'status_harga_order' => 'required|in:Pelunasan,Dp',
            'biaya_lain_harga_order' => 'array',
            'status_pajak' => 'required|in:ya,tidak',
            'setor' => 'required_if:status_kendaraan,Sendiri',
            'uang_jalan' => 'required_if:status_kendaraan,Sendiri',
            'potongan_wajib' => 'required_if:status_kendaraaan,Sendiri',
            'biaya_lain_uang_jalan' => 'array',
            'harga_jual' => 'required_if:status_kendaraan,Subkon',
            'status_harga_jual' => 'required_if:status_kendaraan,Subkon|in:Pelunasan,Dp',
            'biaya_lain_harga_jual' => 'array'
        ], $rule);
    }

    public function messages()
    {
        return [
            'tanggal_awal.required' => 'Tanggal awal harus diisi',
            'tanggal_awal.date' => 'Tanggal awal harus berupa tanggal',
            'tanggal_akhir.required_if' => 'Tanggal akhir harus diisi',
            'tanggal_akhir.date' => 'Tanggal akhir harus berupa tanggal',
            'status_kendaraaan.required' => 'Status kendaraan harus diisi',
            'status_kendaraaan.in' => 'Status kendaraan harus diisi dengan Sendiri atau Subkon',
            'status_kendaraan_sendiri.required_if' => 'Status kendaraan sendiri harus diisi',
            'status_kendaraan_sendiri.in' => 'Status kendaraan sendiri harus diisi dengan Berangkat, Pulang, Kontrak, atau Kota-Kota',
            'status_surat_jalan.required' => 'Status surat jalan harus diisi',
            'status_surat_jalan.in' => 'Status surat jalan harus diisi dengan Sopir, Kantor, atau Selesai',
            'm_penyewa_id.required' => 'Penyewa harus diisi',
            'm_penyewa_id.exists' => 'Penyewa tidak ditemukan',
            'muatan.required' => 'Muatan harus diisi',
            'm_armada_id.required' => 'Armada harus diisi',
            'm_armada_id.exists' => 'Armada tidak ditemukan',
            'm_sopir_id.required' => 'Sopir harus diisi',
            'm_sopir_id.exists' => 'Sopir tidak ditemukan',
            'asal.required' => 'Asal harus diisi',
            'tujuan.required' => 'Tujuan harus diisi',
            'harga_order.required' => 'Harga order harus diisi',
            'harga_order.numeric' => 'Harga order harus berupa angka',
            'status_harga_order.required' => 'Status harga order harus diisi',
            'status_harga_order.in' => 'Status harga order harus diisi dengan Pelunasan atau Dp',
            'biaya_lain_harga_order.array' => 'Biaya lain harga order harus berupa array',
            'status_pajak.required' => 'Status pajak harus diisi',
            'status_pajak.in'   => 'Status pajak harus diisi dengan ya atau tidak',
            'setor.required_if' => 'Setor harus diisi',
            'setor.numeric' => 'Setor harus berupa angka',
            'uang_jalan.required' => 'Uang jalan harus diisi',
            'uang_jalan.numeric' => 'Uang jalan harus berupa angka',
            'potongan_wajib.required' => 'Potongan wajib harus diisi',
            'potongan_wajib.numeric' => 'Potongan wajib harus berupa angka',
            'potongan_wajib.min' => 'Potongan wajib minimal 0',
            'biaya_lain_uang_jalan.array' => 'Biaya lain uang jalan harus berupa array',
            'harga_jual.required_if' => 'Harga jual harus diisi',
            'harga_jual.numeric' => 'Harga jual harus berupa angka',
            'status_harga_jual.required_if' => 'Status harga jual harus diisi',
            'status_harga_jual.in' => 'Status harga jual harus diisi dengan Pelunasan atau Dp',
            'biaya_lain_harga_jual.array' => 'Biaya lain harga jual harus berupa array'
        ];
    }


    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
}
