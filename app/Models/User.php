<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, HasRoles, Sortable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_number',
        'address',
        'city',
        'postcode',
        'degree',
        'department',
        'year_of_passing',
        'status'
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */    

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * The attributes that are sortable.
    *
    * @var array
    */
    
    public $sortable = [
        'id',
        'name',
        'email',
        'mobile_number',
        'created_at',
        'updated_at'
    ];

    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
       $created_at = auth()->user()->created_at;        

        return 'Member Since : '.$created_at;
    }

    public function adminlte_profile_url()
    {
        return 'user/profile/settings';
    }    

}