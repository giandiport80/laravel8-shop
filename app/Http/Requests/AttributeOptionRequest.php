<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeOptionRequest extends FormRequest
{
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
        $attributeID = (int) $this->attribute_id;
        $id = (int) $this->input('id');

        if($this->method() == 'PUT'){
            $name = 'required|unique:attribute_options,name, ' . $id . ',id,attribute_id,' . $attributeID;
        }else{
            $name = 'required|unique:attribute_options,name,null,id,attribute_id,' . $attributeID;
        }

        return [
            'name' => $name
        ];
    }
}










// h: DOKUMENTASI

// $this disini adalah instance object dari FormRequest
// yaitu dari request yang kita berikan dari form
// $this->get() sama saja dengan $this->input()
// hanya saja get berasal dari symfony
// sedangkan input berasal dari laravel
// * kita juga bisa langsung menuliskan $this->attribute_id
// karena sama saja, ini bentuk yang dinamis
