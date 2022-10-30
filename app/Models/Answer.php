<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Answer extends Model
{
    use HasFactory, HasRoles, Sortable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'question_id',
        'name',        
        'status',
        'correct_answer'
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
        'status' => 'string',
        'question_id' => 'string',
        'correct_answer' => 'string'
    ];

    public function scopeCreatedAt(Builder $query, $date): Builder
	{ 
	    return $query->whereDate('created_at', '=', Carbon::parse($date));
	}

    public function scopeQuestionId(Builder $query, $question_id): Builder
    { 
        return $query->where('question_id', '=', $question_id);
    }

    /**
    * Get the questions that owns the answer.
    */

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
        
}
