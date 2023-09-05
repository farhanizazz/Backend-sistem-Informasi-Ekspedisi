<?php

namespace App\Http\Requests\PengeluaranRequest;

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
        return [
            //
            'category' => 'required|in:servis,lain_lain',
            'master_armada_id' => 'required',
            'tgl_transaksi' => 'required',
            'status' => 'required',
            // Add more validation rules as needed for common fields

            // Category-specific fields for "kategori_servis"
            'nama_toko' => 'required_if:category,servis',
            'nomer_nota_beli' => 'required_if:category,servis',

            // Category-specific fields for "lain_lain"
            'nama_tujuan' => 'required_if:category,lain_lain',
            'keterangan' => 'required_if:category,lain_lain',
            'nominal' => 'required_if:category,lain_lain',
            'jumlah' => 'required_if:category,lain_lain',
            'total' => 'required_if:category,lain_lain',

        ];
    }
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
    public function messages()
    {
        return [
            "category.required" => "Kategori tidak boleh kosong",
            "status.required" => "Status tidak boleh kosong",
            "category.in" => "Kategori tidak boleh kosong",
            "master_armada_id.required" => "Nopol tidak boleh kosong",
            "tgl_transaksi.required" => "Tanggal Transaksi tidak boleh kosong",
            "nama_toko.required_if" => "Nama Toko tidak boleh kosong",
            "nomer_nota_beli.required_if" => "Nomer Nota Beli tidak boleh kosong",
            "nama_tujuan.required_if" => "Nama Tujuan tidak boleh kosong",
            "keterangan.required_if" => "Keterangan tidak boleh kosong",
            "nominal.required_if" => "Nominal tidak boleh kosong",
            "jumlah.required_if" => "Jumlah tidak boleh kosong",
            "total.required_if" => "Total tidak boleh kosong",

        ];
    }
}
