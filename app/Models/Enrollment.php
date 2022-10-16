<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Enrollment extends Model
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
        'transaction_id',
        'name',
        'duration',
        'amount',
        'status',
        'expiry_date',
        'total_marks'    
    ];
    
    /**
    * The attributes that are sortable.
    *
    * @var array
    */

    public $sortable = [
        'id',
        'name',
        'duration',
        'amount',
        'status',
        'created_at',
        'updated_at'
    ];   

    public function scopeCreatedAt(Builder $query, $date): Builder
    { 
        return $query->whereDate('created_at', '=', Carbon::parse($date));
    }  

    /**
    * Scope a query to only include active enrollments.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */

    public function scopeActive($query)
    {
        return $query->where('status', 1)->whereDate('expiry_date','>',now());
    }
}
