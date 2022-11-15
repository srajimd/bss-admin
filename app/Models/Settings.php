<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Settings extends Model
{
    use HasFactory, HasRoles, Sortable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'hard_copy_charge',
        'total_marks'
    ];

    /**
    * The attributes that are sortable.
    *
    * @var array
    */

    public $sortable = [
        'id',
        'created_at',
        'updated_at'
    ];   

    protected $casts = [ 
        'id' => 'string',
        'hard_copy_charge'=>'string',
        'total_marks' => 'string'
    ]; 
    
    public function scopeActive(Builder $query, $hard_copy_charge, $total_marks): Builder
    { 
        return $query->where('hard_copy_charge', '=', $hard_copy_charge)
        ->where('total_marks', '=', $total_marks)
        ->where('delete_flag', '=', 'N');
    }

}
