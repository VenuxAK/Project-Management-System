<?php

namespace App\Http\Requests\Projects;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->isAdministrator() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "min:3", "max:50"],
            "email" => [
                "required",
                "string",
                "email",
                "lowercase",
                Rule::unique(User::class)->ignore($this->route('user')),
            ],
            "role_id" => [
                "required",
                Rule::exists('roles', 'id')->where('scope', 'global'),
            ],
        ];
    }
}
