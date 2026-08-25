<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AttachProjectMembersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('manageMembers', $this->route('project'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "members" => ["required", "array", "min:1"],
            "members.*.user_id" => [
                "required",
                "distinct",
                Rule::exists('users', 'id'),
            ],
            "members.*.role_id" => ["required", Rule::exists('roles', 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            "members.required" => "Please add at least one member.",
            "members.*.user_id.required" => "The user field is required.",
            "members.*.role_id.required" => "The role field is required.",
        ];
    }
}
