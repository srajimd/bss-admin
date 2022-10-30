<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Receipt extends Model
{
    use HasFactory, HasRoles, Sortable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'user_id',
        'course_id',
        'file_path'
    ];

    /**
    * The attributes that are sortable.
    *
    * @var array
    */

    public $sortable = [
        'id',
        'user_id',
        'course_id',
        'created_at',
        'updated_at'
    ];   

    protected $casts = [ 
        'id' => 'string', 
        'course_id' => 'string',
        'user_id' => 'string'
    ];

    public function scopeCreatedAt(Builder $query, $date): Builder
    { 
        return $query->whereDate('receipts.created_at', '=', Carbon::parse($date));
    }

    public function scopeCourseId(Builder $query, $course_id): Builder
    { 
        return $query->where('course_id', '=', $course_id);
    }    

    /**
    * Get the course that owns the syllabus.
    */
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }   

}
