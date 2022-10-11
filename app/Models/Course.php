<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Course extends Model
{
    use HasFactory, HasRoles, Sortable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'topic_id',
        'name',
        'course_identifier',
        'meta_title',
        'meta_description',
        'duration',
        'amount',
        'certification',
        'other_information',        
        'status'
    ];

    /**
    * The attributes that are sortable.
    *
    * @var array
    */

    public $sortable = [
        'id',
        'course_identifier',
        'name',
        'duration',
        'amount',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    /*protected $appends = ['is_admin'];*/

    public function scopeCreatedAt(Builder $query, $date): Builder
	{ 
	    return $query->whereDate('created_at', '=', Carbon::parse($date));
	}

    public function scopeTopicId(Builder $query, $topic_id): Builder
    { 
        return $query->where('topic_id', '=', $topic_id);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    /**
    * Set the duration.
    *
    * @param  string  $value
    * @return void
    */

    public function setDurationAttribute($value)
    {
        $this->attributes['duration'] = (int)$value;
    }

    /**
    * Set the amount.
    *
    * @param  string  $value
    * @return void
    */

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (float)$value;
    }

    /**
    * Get the topic that owns the course.
    */

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
    * Get the syllabuses for the course.
    */

    public function syllabi()
    {
        return $this->hasMany(Syllabus::class);
    }

    /**
    * Get the videos for the course.
    */

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
    * Get the documents for the course.
    */
    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Determine if the user is an administrator.
     *
     * @return bool
     */
   /* public function getAttribute()
    {
        return $this->attributes['certification'] === 'yes';
    }*/
}
