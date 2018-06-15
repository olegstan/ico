<?php
namespace App\Http\Requests\Lobby;

use Illuminate\Foundation\Http\FormRequest;

class OpenSessionRequest extends FormRequest
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
        //todo add chech exists bet
        //todo check enought credits



        return [
            'bet_id' => 'required',
        ];
    }
}
