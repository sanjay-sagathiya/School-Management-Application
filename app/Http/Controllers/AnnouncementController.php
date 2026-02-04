<?php

namespace App\Http\Controllers;

use App\Actions\Notification\Contracts\Notification;
use App\Http\Requests\AnnouncementStoreRequest;
use App\Http\Requests\AnnouncementUpdateRequest;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
		$user = auth()->user();

		$announcements = Announcement::query()
			->when($user->isAdmin(), function ($query) {
				$query->withoutGlobalScope('teacher');
				$query->with('user:id,name');
			})
			->latest()
			->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function store(AnnouncementStoreRequest $request, Notification $notification)
    {
        $announcement = Announcement::create($request->validated());

		$receiver = auth()->user()->isAdmin() ? 'teachers' : $request->input('receiver');

		// Send notifications based on receiver type
		$notification->send($announcement, $receiver);

        return response()->json(['success' => true, 'message' => 'Announcement created.', 'announcement' => $announcement], 201);
    }

    public function edit(Announcement $announcement)
    {
        return response()->json($announcement);
    }

    public function update(AnnouncementUpdateRequest $request, Announcement $announcement)
    {
        $announcement->update($request->validated());

        return response()->json(['success' => true, 'message' => 'Announcement updated.', 'announcement' => $announcement]);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json(['success' => true, 'message' => 'Announcement deleted.']);
    }
}
