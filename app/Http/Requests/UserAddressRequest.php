<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'province'      => 'required',
            'city'          => 'required',
            'district'      => 'required',
            'address'       => 'required',
            'zip'           => 'required',
            'contact_name'  => 'required',
            'contact_phone' => 'required|digits:11',
        ];
    }
    public function messages ()
    {
        return [
            'province.required'=>'请选择省',
            'city.required'=>'请选择市',
            'district.required'=>'请选择区',
            'address.required'=>'请输入详细地址',
            'zip.required'=>'请输入区号',
            'contact_name.required'=>'请输入联系人',
            'contact_phone.required'=>'请输入联系方式',
            'contact_phone.digits'=>'请输入正确手机号',
        ];
    }
}
