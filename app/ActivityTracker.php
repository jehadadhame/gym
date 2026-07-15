<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityTracker extends Model
{
    protected $table = 'activity_trackers';

    protected $fillable = [
        'user_id',
        'action_type',
        'affected_member_name',
        'story'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
