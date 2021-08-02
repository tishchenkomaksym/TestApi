<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
        return [
            'title' => 'required|string|min:10|unique:articles',
            'body' => 'required|string|min:100',
            'image' => 'image|mimes:jpeg, jpg, png|max:2048',
            'tag_id' => 'numeric'
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
