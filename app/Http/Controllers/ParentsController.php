<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentsStoreRequest;
use App\Http\Requests\ParentsUpdateRequest;
use App\Models\Parents;

class ParentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

		$parents = Parents::query()
			->when($user->role === 'admin', function ($query) {
				$query->withoutGlobalScope('teacher');
				$query->with('teacher');
			})
			->latest()
			->paginate(10);

		return view('parents.index', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ParentsStoreRequest $request)
    {
		$data = $request->validated();

        $parent = Parents::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Parent created successfully.',
            'parent' => $parent
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parents $parent)
    {
        return response()->json($parent);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ParentsUpdateRequest $request, Parents $parent)
    {
		$data = $request->validated();

        $parent->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Parent updated successfully.',
            'parent' => $parent
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parents $parent)
    {
        $parent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Parent deleted successfully.'
        ]);
    }
}
