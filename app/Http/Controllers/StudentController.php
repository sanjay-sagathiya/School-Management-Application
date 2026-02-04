<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
		$user = auth()->user();

		$students = Student::query()
			->when($user->role === 'admin', function ($query) {
				$query->withoutGlobalScope('teacher');
				$query->with('teacher:id,name');
			})
			->latest()
			->paginate(10);

		return view('students.index', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentStoreRequest $request)
    {
        $data = $request->validated();

        $student = Student::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully.',
            'student' => $student
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentUpdateRequest $request, Student $student)
    {
        $data = $request->validated();

        $student->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully.',
            'student' => $student
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully.'
        ]);
    }
}
