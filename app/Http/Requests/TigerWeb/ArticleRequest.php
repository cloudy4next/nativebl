<?php

namespace App\Http\Requests\TigerWeb;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation(): void
    {
        // Assuming you want to add an 'author_id' to the request data:
        if (!$this->id) {
            $this->merge([
                'slug' => Str::lower(Str::slug($this->title, '-')), // or any other value you want to add
            ]);
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'article_category_id' => 'required|string',
            'tag_name' => 'required|string',
            'content' => 'required|string',
        ];

        if (!$this->id) {
            $rules['slug'] = 'required|string|unique:articles';
        }
        return $rules;
    }
}
