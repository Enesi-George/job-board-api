<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasUlids;

    protected $table = "applications";

    protected $fillable = [
        'job_id',
        'first_name',
        'last_name',
        'email',
        'location',
        'phone_number',
        'document',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
