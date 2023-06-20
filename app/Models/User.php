<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_identifier',
        'birth_date',
        'email',
        'password',
        'phone_number',
        'current_location',
        'programming_age',
        'gender',
        'bio',
        'image_path',
        'country',
        'media_id'
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
        'password' => 'hashed',
    ];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function expert(): HasOne
    {
        return $this->hasOne(Expert::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'admin_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function favoritePosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'favorite_posts')
            ->using(FavoritePost::class)
            ->as('favorite')
            ->withTimestamps();
    }

    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class)
            ->as('subscription')
            ->withTimestamps();
    }

    public function memberPages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class)
            ->as('member')
            ->withTimestamps();
    }

    public function specialty(): HasOne
    {
        return $this->hasOne(Specialty::class);
    }

    public function locationPosts(): MorphMany
    {
        return $this->morphMany(Post::class, 'location');
    }

    public function senders(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'friends',
            'receiver',
            'sender'
        )
            ->using(Friend::class)
            ->as('sender')
            ->withPivot('is_approved')
            ->withTimestamps();
    }

    public function receivers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'friends',
            'sender',
            'receiver'
        )
            ->using(Friend::class)
            ->as('receiver')
            ->withPivot('is_approved')
            ->withTimestamps();
    }

    public function inviters(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'invites',
            'receiver',
            'sender'
        )
            ->using(Invite::class)
            ->as('inviters')
            ->withPivot('is_approved')
            ->withTimestamps();
    }

    public function invitees(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'invites',
            'sender',
            'receiver'
        )
            ->using(Invite::class)
            ->as('invitee')
            ->withPivot('is_approved')
            ->withTimestamps();
    }
}
