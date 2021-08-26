<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AskQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // NOTE : You can place gates only here. This is not a wise place to perform any authorization.
        // Instead
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
            'title' => 'required|max:255|unique:questions' . ($this->question ? (',title,' . $this->question->title . ',title') : ''),
            'body' => 'required'
        ];




    }
}
