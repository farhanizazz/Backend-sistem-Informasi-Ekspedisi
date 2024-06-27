<?php

namespace App\Http\Requests\ServisRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'master_armada_id' => 'required|exists:master_armada,id',
            // 'nama_barang' => 'required|string',
            'nama_toko' => 'required_if:kategori_servis,servis|string',
            'nomor_nota' => 'required_if:kategori_servis,servis|string',
            'tanggal_servis' => 'required|date',
            'kategori_servis' => 'required|string',
            'nota_beli_items.*.nama_barang' => 'required|string',
            'nota_beli_items.*.harga' => 'required|numeric',
            'nota_beli_items.*.jumlah' => 'required|integer',
            "kategori_servis" => "required|string",
            "nama_tujuan_lain" => "required_if:kategori_servis,lain|string",
            "keterangan_lain" => "required_if:kategori_servis,lain|string",
            "nominal_lain" => "required_if:kategori_servis,lain|string",
            "jumlah_lain" => "required_if:kategori_servis,lain|integer",
            "total_lain" => "required_if:kategori_servis,lain|integer",
        ];
    }
    public function messages()
    {
        return [
            'master_armada_id.required' => 'Master armada is required',
            // 'nama_barang.required' => 'Nama barang is required',
            // 'nama_barang.string' => 'Nama barang must be a string',
            'nomor_nota.required_if' => 'Nomor Nota is required',
            'nomor_nota.string' => 'Nomor nota must be a string',
            'harga.required' => 'Harga is required',
            'jumlah.required' => 'Jumlah is required',
            'jumlah.integer' => 'Jumlah must be an integer',
            'nama_toko.required_if' => 'Nama toko is required',
            'nama_toko.string' => 'Nama toko must be a string',
            'tanggal_servis.required' => 'Tanggal servis is required',
            'tanggal_servis.date' => 'Tanggal servis must be a date',
            'nota_beli_items.*.nama_barang.required' => 'Nama barang is required',
            'nota_beli_items.*.nama_barang.string' => 'Nama barang must be a string',
            'nota_beli_items.*.harga.required' => 'Harga is required',
            'nota_beli_items.*.harga.numeric' => 'Harga must be a number',
            'nota_beli_items.*.jumlah.required' => 'Jumlah is required',
            'nota_beli_items.*.jumlah.integer' => 'Jumlah must be an integer',
            'kategori_servis.required' => 'Kategori servis is required',
            'kategori_servis.string' => 'Kategori servis must be a string',
            'nama_tujuan_lain.required_if' => 'Nama tujuan lain is required',
            'nama_tujuan_lain.string' => 'Nama tujuan lain must be a string',
            'keterangan_lain.required_if' => 'Keterangan lain is required',
            'keterangan_lain.string' => 'Keterangan lain must be a string',
            'nominal_lain.required_if' => 'Nominal lain is required',
            'nominal_lain.string' => 'Nominal lain must be a string',
            'jumlah_lain.required_if' => 'Jumlah lain is required',
            'jumlah_lain.integer' => 'Jumlah lain must be an integer',
            'total_lain.required_if' => 'Total lain is required',
            'total_lain.integer' => 'Total lain must be an integer',
            
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
    public function validated()
    {
        $validated = parent::validated();
    }   
}
