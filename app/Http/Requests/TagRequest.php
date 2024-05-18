<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
        $rules = [
            'tags' => 'required|array|min:1',
            'tags.*.name' => 'max:20|required_with:tags.*.icon',
            'tags.0.name' => 'required',
        ];
        return $rules;
    }

}
