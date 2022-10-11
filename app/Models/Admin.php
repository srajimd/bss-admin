<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;


class Admin extends Authenticatable
{

    use HasFactory, Notifiable, HasRoles, Sortable;

    protected $guard = 'admin';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'name',
        'email',
        'password',
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

    /**
    * The attributes that are sortable.
    *
    * @var array
    */

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $sortable = [
        'id',
        'name',
        'email',
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
        return 'admin/profile/settings';
    }

}