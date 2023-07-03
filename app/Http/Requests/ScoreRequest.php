<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_club_id' => [
                'required',
                Rule::unique('scores')->where(function ($query) {
                    return $query->where('second_club_id', $this->input('second_club_id'));
                }),
            ],
            'second_club_id' => [
                'required',
                Rule::unique('scores')->where(function ($query) {
                    return $query->where('first_club_id', $this->input('first_club_id'));
                }),
            ],
            'score_first_club' => ['required'],
            'score_second_club' => ['required'],
        ];
    }
}
