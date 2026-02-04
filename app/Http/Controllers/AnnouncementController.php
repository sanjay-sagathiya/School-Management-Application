<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementStoreRequest;
use App\Http\Requests\AnnouncementUpdateRequest;
use App\Models\Announcement;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AdminAnnouncement;
use App\Notifications\TeacherAnnouncement;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    public function index()
    {
		$role = auth()->user()->role === 'admin' ? 'admin' : 'teacher';

        $announcements = Announcement::role($role)->latest()->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function store(AnnouncementStoreRequest $request)
    {
        $announcement = Announcement::create($request->validated());

		if (auth()->user()->role === 'admin') {
			// Send notification to all teachers
			$teachers = User::role('teacher')->get();

			// Send notification
			Notification::send($teachers, new AdminAnnouncement($announcement));
		} else {
			foreach (Student::cursor() as $student) {
				$student->notify(new TeacherAnnouncement($announcement));
			}
		}

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
