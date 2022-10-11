<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Unit extends Model
{
    use HasFactory, HasRoles, Sortable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'syllabus_id',
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
        'updated_at'
    ];   

    public function scopeCreatedAt(Builder $query, $date): Builder
	{ 
	    return $query->whereDate('created_at', '=', Carbon::parse($date));
	}

    public function scopeSyllabusId(Builder $query, $syllabus_id): Builder
    { 
        return $query->where('syllabus_id', '=', $syllabus_id);
    }

    /**
    * Get the syllabus that owns the unit.
    */
    
    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class);
    }
        
}
