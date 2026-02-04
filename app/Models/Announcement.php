<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'description',
    ];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function scopeRole(Builder $query, string $role): Builder
    {
        return $query->whereHas('user', function (Builder $query) use ($role) {
				$query->where('role', $role);
			});
    }

	public static function boot()
	{
		parent::boot();

		static::creating(function ($announcement) {
			$announcement->user_id = auth()->id();
		});
	}
}
