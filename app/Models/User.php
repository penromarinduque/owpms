<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'usertype',
        'is_active_user',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    const TYPE_ADMIN  = "admin";
    const TYPE_INTERNAL  = "internal";
    const TYPE_PERMITTEE = "permittee";
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function wildlifePermits()
    {
        return $this->hasMany(Permittee::class, 'user_id');
    }

    public function wcp(){
        return $this->wildlifePermits()->where('permit_type', Permittee::PERMIT_TYPE_WCP)->first();
    }

    public function wfp(){
        return $this->wildlifePermits()->where('permit_type', Permittee::PERMIT_TYPE_WFP)->first();
    }

    public function personalInfo(){
        return $this->hasOne(PersonalInfo::class);
    }

    public function userRoles(){
        return $this->hasMany(UserRole::class, 'user_id', 'id');
    }   
}
