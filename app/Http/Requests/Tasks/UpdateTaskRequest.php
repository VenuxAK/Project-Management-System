<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->task);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string"],
            "priority" => ["required", "string", "in:low,medium,high"],
            "status" => ["required", "string", "in:pending,in_progress,completed"],
            "assigned_to" => ["required", "exists:users,id"],
            "project_id" => ["required", "exists:projects,id"],
            "start_date" => ["required", "date"],
            "due_date" => ["required", "date", "after_or_equal:start_date"],
        ];
    }
}
