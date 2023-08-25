<?php

namespace App\Http\Requests\RoleRequest;

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
            'name' => 'required|unique:m_role,name' . $this->name,
            'akses'=> 'required'
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Nama role tidak boleh kosong',
            'name.unique' => 'Nama role sudah ada, silahkan gunakan nama lain',
            'akses.required' => 'Akses tidak boleh kosong'
        ];
    }
    
    
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
}
