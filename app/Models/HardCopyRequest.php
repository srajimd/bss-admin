<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class HardCopyRequest extends Model
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
        'enrollment_id',
        'address1',
        'country',
        'city',
        'state',
        'zipcode',
        'mobile'
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
        'user_id' => 'string',
        'enrollment_id' => 'string'
    ];

    public function scopeCreatedAt(Builder $query, $date): Builder
    { 
        return $query->whereDate('HardCopyRequest.created_at', '=', Carbon::parse($date));
    }

    public function scopeCourseId(Builder $query, $course_id): Builder
    { 
        return $query->where('course_id', '=', $course_id);
    }  

    public function scopeCertificateId(Builder $query, $certificate_id): Builder
    { 
        return $query->where('certificate_id', '=', $certificate_id);
    }   

    /**
    * Get the course that owns the syllabus.
    */
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    } 
    
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    } 

}
