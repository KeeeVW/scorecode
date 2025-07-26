<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model  {

    protected $fillable = [
        'user_id',
        'course_id',
        'degree',
        'comments',
        'appeal_status',
        'appeal_reason'
    ];

    protected $casts = [
        'degree' => 'decimal:2'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function submitAppeal($reason)
    {
        $this->update([
            'appeal_status' => 'pending',
            'appeal_reason' => $reason
        ]);
    }

    public function closeAppeal()
    {
        $this->update([
            'appeal_status' => 'closed'
        ]);
    }
}