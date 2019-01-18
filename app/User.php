<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'activation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    const PASSWORD_COMPLEXITY = array(
        'validation' => 'required|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        'errorMessage' => 'Password must be alphanumeric, contains upper & lowercase characters and digits.'
    );

    public function roles()
    {
        return $this->belongsToMany('App\Role','user_role','user_id','role_id');
    }

    /**
     * Checks this user's role
     * @var $roles Can be array or string
     * @return boolean
     */
    public function hasAnyRoles($roles)
    {
        if(is_array($roles))
        {
            foreach($roles as $role)
            {
                if($this->hasRole($role))
                {
                    return true;
                }
            }
        }
        else
        {
            return $this->hasRole($roles);
        }

        return false;
    }

    /** Checks this user's role
    * @var string $role Name of the role
    * @return boolean
    */
    private function hasRole($role)
    {
        $hasRole = $this->roles()->where('name',$role)->first();

        return $hasRole ? true : false;
    }

}
