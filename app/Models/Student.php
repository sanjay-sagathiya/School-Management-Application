<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use Notifiable;

	/**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
		'teacher_id',
        'name',
        'email',
    ];

	public function teacher()
	{
		return $this->belongsTo(User::class, 'teacher_id', 'id');
	}

	public static function boot()
	{
		parent::boot();

		static::creating(function ($student) {
			$student->teacher_id = auth()->id();
		});

		static::addGlobalScope('teacher', function ($builder) {
			if (auth()->check() && auth()->user()->role === 'teacher') {
				$builder->where('teacher_id', auth()->id());
			}
		});
	}
}
