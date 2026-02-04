<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherStoreRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Models\User;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = User::role('teacher')->latest()->paginate(10);

        return view('teachers.index', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherStoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt('Pa$$w0rd!');

        $teacher = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Teacher created successfully.',
            'teacher' => $teacher
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $teacher)
    {
        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherUpdateRequest $request, User $teacher)
    {
        $data = $request->validated();

        $teacher->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully.',
            'teacher' => $teacher
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $teacher)
    {
        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher deleted successfully.'
        ]);
    }
}
