<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category_id' => 'required|numeric|exists:categories,id',
            'chapter_id' => 'required|numeric|exists:chapters,id',
            'title' => 'required|min:3',
            'description' => 'required|min:10',
            'content' => 'required|min:10'
        ];
    }
}
