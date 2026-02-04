<?php

namespace App\Actions\Notification;

use App\Actions\Notification\Contracts\Notification as ContractsNotification;
use App\Models\Announcement;
use App\Models\Parents;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AdminAnnouncement;
use App\Notifications\TeacherAnnouncement;

class Notification implements ContractsNotification
{
	public function send(Announcement $announcement, string $receiver)
	{
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
		// Send notification to all parents
		foreach (Parents::cursor() as $parent) {
			$parent->notify(new TeacherAnnouncement($announcement));
		}
	}

	public function sendNotificationToBoth(Announcement $announcement)
	{
		// Send notification to all parents and students
		$this->sendNotificationToParents($announcement);
		$this->sendNotificationToStudents($announcement);
	}
}
