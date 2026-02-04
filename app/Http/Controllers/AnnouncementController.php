<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementStoreRequest;
use App\Http\Requests\AnnouncementUpdateRequest;
use App\Models\Announcement;
use App\Models\Parents;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AdminAnnouncement;
use App\Notifications\TeacherAnnouncement;
use Symfony\Component\HttpFoundation\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
		$user = auth()->user();

		$announcements = Announcement::query()
			->when($user->role === 'admin', function ($query) {
				$query->withoutGlobalScope('teacher');
				$query->with('user:id,name');
			})
			->latest()
			->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function store(AnnouncementStoreRequest $request)
    {
        $announcement = Announcement::create($request->validated());

		$this->sendNotifications($announcement, $request);

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

	public function sendNotifications(Announcement $announcement, Request $request)
	{
		$receiver = auth()->user()->role === 'admin' ? 'teachers' : $request->input('receiver');

		$handlers = [
			'teachers' => 'sendNotificationToTeachers',
			'students' => 'sendNotificationToStudents',
			'parents'  => 'sendNotificationToParents',
			'both'     => 'sendNotificationToBoth',
		];

		if (isset($handlers[$receiver])) {
			$this->{$handlers[$receiver]}($announcement);
		}
	}

	public function sendNotificationToTeachers(Announcement $announcement)
	{
		// Send notification to all teachers
		foreach (User::role('teacher')->cursor() as $teacher) {
			$teacher->notify(new AdminAnnouncement($announcement));
		}
	}

	public function sendNotificationToStudents(Announcement $announcement)
	{
		// Send notification to all students
		foreach (Student::cursor() as $student) {
			$student->notify(new TeacherAnnouncement($announcement));
		}
	}

	public function sendNotificationToParents(Announcement $announcement)
	{
		// Send notification to all students
		foreach (Parents::cursor() as $parent) {
			$parent->notify(new TeacherAnnouncement($announcement));
		}
	}

	public function sendNotificationToBoth(Announcement $announcement)
	{
		$this->sendNotificationToParents($announcement);
		$this->sendNotificationToStudents($announcement);
	}
}
