<?php

namespace App\Rules;

use App\Http\Traits\GlobalTrait;
use Illuminate\Contracts\Validation\Rule;

class CekBiayaLainRule implements Rule
{
    use GlobalTrait;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = $this->checkArrayIssetOnRequest($value, ["m_tambahan_id", "nominal"], $attribute);
        if ($result['status'] == false) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ":attribute harus berupa array dengan key m_tambahan_id dan nominal, cth : [{m_tambahan_id:1,nominal:10000}]";
    }
}
