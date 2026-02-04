<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'description',
		'receiver'
    ];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public static function boot()
	{
		parent::boot();

		static::creating(function ($announcement) {
			$announcement->user_id = auth()->id();
		});

		static::addGlobalScope('teacher', function ($builder) {
			if (auth()->check() && auth()->user()->isTeacher()) {
				$builder->where('user_id', auth()->id());
			}
		});
	}
}
