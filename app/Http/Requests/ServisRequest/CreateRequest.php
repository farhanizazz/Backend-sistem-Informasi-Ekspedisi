<?php

namespace App\Http\Requests\ServisRequest;

use App\Models\Transaksi\NotaBeliModel;

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
        // Replace with your own logic
        return true;
    }

    public function rules()
{
    return [
        'master_armada_id' => 'required|exists:master_armada,id',
        // 'nama_barang' => 'required|string',
        'nomor_nota' => 'required_if:kategori_servis,servis|string',
        'nama_toko' => 'required_if:kategori_servis,servis|string',
        'tanggal_servis' => 'required|date',
        'nota_beli_items.*.nama_barang' => 'required|string',
        'nota_beli_items.*.harga' => 'required|numeric',
        'nota_beli_items.*.jumlah' => 'required|integer',
        "kategori_servis" => "required|string",
        "nama_tujuan_lain" => "required_if:kategori_servis,lain",
        "keterangan_lain" => "required_if:kategori_servis,lain",
        "nominal_lain" => "required_if:kategori_servis,lain",
        "jumlah_lain" => "required_if:kategori_servis,lain",
        "total_lain" => "required_if:kategori_servis,lain",
    ];
}

    public function messages()
    {
        // Replace with your own messages
        return [
            // 'field.rule' => 'message'
            'master_armada_id.required' => 'Master armada is required',
            // 'nama_barang.required' => 'Nama barang is required',
            // 'nama_barang.string' => 'Nama barang must be a string',
            'nomor_nota.required_if' => 'Nomor nota is required',
            'nomor_nota.string' => 'Nomor nota must be a string',
            'harga.required' => 'Harga is required',
            'jumlah.required' => 'Jumlah is required',
            'jumlah.integer' => 'Jumlah must be an integer',
            'nama_toko.required_if' => 'Nama toko is required',
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

   
}
