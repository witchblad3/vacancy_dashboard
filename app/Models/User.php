<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Vacancy;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ADMIN_ROLE = 1;
    const USER_ROLE = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login', 'first_name', 'email', 'last_name', 'bdate', 'exp', 'password', 'jobgiver'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role == self::ADMIN_ROLE;
    }

    public function isUsualUser(): bool
    {
        return $this->role == self::USER_ROLE;
    }

    public function isJobGiver(): bool
    {
        return $this->jobgiver;
    }

    public function hasUnreadMail(): bool
    {
        $unreadMailsCount = $this->receivedMails()
            ->where('is_read', '=', '0')
            ->count();
        return $unreadMailsCount > 0;
    }

    public function vacancies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Vacancy::class, 'author_id');
    }

    public function favvacs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FavVacs::class)
            ->join('vacancies', 'vac_id', 'vacancies.id')
            ->select('vacancies.*')->where('vacancies.deleted_at', '=', null);
    }

    public function receivedMails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MailBox::class, 'recipient', 'id');
    }

    public function sentMails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MailBox::class, 'sender_id', 'id');
    }
}
