<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $authUser = $this->user();
		$teacher = $this->route('teacher');

		if (! $authUser || ! $teacher) {
			return false;
		}

		return $authUser->role === 'admin'
			|| $authUser->id === $teacher->id;
    }

    public function rules(): array
    {
        $teacherId = $this->route('teacher') ? $this->route('teacher')->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($teacherId)],
        ];
    }
}
