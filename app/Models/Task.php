<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'user_id'
    ];

    public function taskFiles()
    {
        return $this->hasMany(TaskFile::class);
    }
}
