<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasUlids;

    protected $table = "jobs_board";

    protected $fillable = [
        'user_id',
        'title',
        'company',
        'company_logo',
        'location',
        'category',
        'salary',
        'qualifications',
        'descriptions',
        'benefit',
        'type',
        'work_condition',
    ];

    protected $casts = [
        'qualifications' => 'array',
        'descriptions' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
