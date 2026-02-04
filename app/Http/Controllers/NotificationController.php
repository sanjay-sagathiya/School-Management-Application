<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifications()
	{
		if (auth()->user()->role == 'admin') {
			return redirect()->route('dashboard');
		}

		$notifications = auth()->user()
			->notifications()
			->latest()
			->paginate(10);

		return view('notifications.index', compact('notifications'));
	}

	public function markAsRead($id)
	{
		$notification = auth()->user()->notifications()->where('id', $id)->firstOrfail();

		$notification->markAsRead();

		return response()->json([
			'success' => true,
			'message' => 'Notification marked as read.'
		]);
	}
}
