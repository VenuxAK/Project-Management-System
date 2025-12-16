<?php

namespace App\Http\Requests\Tasks;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Task::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "priority" => ["required", "in:low,medium,high"],
            "status" => ["required", "in:pending,in_progress,completed"],
            "start_date" => ["required", "date"],
            "due_date" => ["required", "date", "after_or_equal:start_date"],
            "project_id" => ["required", "exists:projects,id"],
            "assigned_to" => ["required", "exists:users,id"],
        ];
    }

    public function messages(): array
    {
        return [
            "project_id.required" => "Please select a project for the task.",
            "assigned_to.required" => "Please assign the task to a user.",
        ];
    }
}
