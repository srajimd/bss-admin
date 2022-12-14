<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Syllabus extends Model
{
    use HasFactory, HasRoles, Sortable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'topic_id',
        'course_id',
        'name',        
        'status'
    ];

    /**
    * The attributes that are sortable.
    *
    * @var array
    */

    public $sortable = [
        'id',
        'name',        
        'status',
        'created_at',
        'updated_at',        
    ];  
    
    protected $casts = [ 
        'id' => 'string',    
        'status' => 'string',
        'course_id' => 'string'
    ];

    public function scopeCreatedAt(Builder $query, $date): Builder
	{ 
	    return $query->whereDate('syllabi.created_at', '=', Carbon::parse($date));
	}

    public function scopeCourseId(Builder $query, $course_id): Builder
    { 
        return $query->where('course_id', '=', $course_id);
    }

    /**
    * Get the topic that owns the syllabus.
    */

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }  

    /**
    * Get the course that owns the syllabus.
    */

    public function course()
    {
        return $this->belongsTo(Course::class);
    }   

    /**
    * Get the units for the syllabus.
    
    */
    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    /**
    * Get the units for the syllabus.
    */
    
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    } 
}
