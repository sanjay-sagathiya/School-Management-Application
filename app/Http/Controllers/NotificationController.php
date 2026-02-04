<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifications()
	{
		$user = auth()->user();

		if ($user->isAdmin()) {
			return redirect()->route('dashboard');
		}

		$notifications = $user->notifications()
			->latest()
			->paginate(10);

		return view('notifications.index', compact('notifications'));
	}

	public function markAsRead($id)
	{
		$user = auth()->user();

		$notification = $user->notifications()->where('id', $id)->firstOrfail();

		$notification->markAsRead();

		return response()->json([
			'success' => true,
			'message' => 'Notification marked as read.'
		]);
	}
}
