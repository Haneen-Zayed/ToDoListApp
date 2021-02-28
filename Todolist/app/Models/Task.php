<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;


    protected $fillable = [
        'content',
        'user_id',
        'the_day',
        'status',
        'task_dat'
    ]; 

     public function user()
    {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }
}
