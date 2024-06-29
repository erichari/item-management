<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return $this->user()->id === $this->route('items')->user_id;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $route= $this->route()->getName();

        $rules = [
            'image' => 'file|image|mimes:jpeg,png,jpg',
            'title' => 'required|max:40',
            'serving' => 'max:40',
            'ingredients' => 'array|min:1',
            'ingredients.*' => 'array|min:1',
            'ingredients.*.name' => 'max:40',
            'ingredients.*.quantity' => 'max:40',
            'processes' => 'array|min:1',
            'processes.name' => 'array|min:1',
            'processes.*.name' => 'max:400|required_with:processes.image.*',
            'processes.*.image' => 'file|image|mimes:jpeg,png,jpg|max:1024',
            'memo' => 'max:400',
        ];

        switch ($route) {
            case 'add':
            case 'edit':
                $rules['ingredients.*.name'] = 'max:40|required_with:ingredients.*.quantity';
                $rules['ingredients.*.quantity'] = 'max:40|required_with:ingredients.*.name';
                $rules['ingredients.0.name'] = 'max:40|required';
                $rules['processes.*.name'] = 'max:400|required_with:processes.*.image';
                $rules['processes.0.name'] = 'required|max:400|required_with:processes.image.*';
                break;

            // case 'user.update': // レコード編集のためのルールを追加
            //     $rules['email'] = [
            //         'required',
            //         'email',
            //         Rule::unique('users')->ignore($user->id), //現在のレコード以外のレコードで重複がないかチェック
            //     ];
            //     break;
        }
        return $rules;
    }

}
