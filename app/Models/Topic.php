<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Topic extends Model
{
    use HasFactory, HasRoles, Sortable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'name',
        'meta_title',
        'meta_description',
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
        'updated_at'
    ];

    protected $casts = [ 
        'id' => 'string',    
        'status' => 'string'
    ];

    public function scopeCreatedAt(Builder $query, $date): Builder
	{ 
	    return $query->whereDate('created_at', '=', Carbon::parse($date));
	}

    /**
    * Get the courses for the topic.
    */
    
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

     /**
    * Get the syllabuses for the topic.
    */
    
    public function syllabi()
    {
        return $this->hasMany(Syllabus::class);
    }

    /**
    * Get the documents for the topic.
    */
    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
