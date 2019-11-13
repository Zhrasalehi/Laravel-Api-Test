<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 * @property int is_completed
 * @package App\Models
 */
class Task extends Model
{
    protected $fillable = [
        'text',
        'user_id',
        'is_completed',
    ];
}
