<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationViewedBy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_id'
    ];

    protected $table = 'notification_viewed_by';

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
