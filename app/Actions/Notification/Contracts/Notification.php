<?php

namespace App\Actions\Notification\Contracts;

use App\Models\Announcement;

interface Notification
{
	public function send(Announcement $notifiable, string $receiver);
}
