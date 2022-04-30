<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use \OwenIt\Auditing\Auditable;
    use \Lab404\Impersonate\Models\Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password', 'current_team_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function getTeamRole()
    {
        return $this->teamRole($this->currentTeam());
    }

    public function session()
    {
        return $this->hasOne(UserSession::class)
                ->ofMany([
                'sessions.last_activity' => 'max',
            ], function ($query) {
                $query->where('sessions.user_id', $this->id)->orderBy('sessions.last_activity', 'desc');
            })->orderBy('sessions.last_activity', 'desc');
    }

    public function canImpersonate()
    {
        return boolval($this->super_admin);
    }

    public function canBeImpersonated()
    {
        return !boolval($this->super_admin);
    }
}
