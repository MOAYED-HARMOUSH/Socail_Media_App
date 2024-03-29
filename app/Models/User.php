<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements HasMedia, MustVerifyEmail  //1
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        InteractsWithMedia;

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
        'country','Approvals_counter'
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
    public function agrees(): HasMany
    {
        return $this->hasMany(Agree::class);
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
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
    public function counters(): HasMany
    {
        return $this->hasMany(counterpost::class);
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

    public function searchHistory(): HasOne
    {
        return $this->hasOne(SearchHistory::class);
    }

    public function senders(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'friends',
            'sender',
            'receiver'
        )
            ->using(Friend::class)
            ->as('sender')
            ->withPivot(['is_approved', 'id'])
            ->withTimestamps();
    }

    public function receivers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'friends',
            'receiver',
            'sender'
        )
            ->using(Friend::class)
            ->as('receiver')
            ->withPivot(['is_approved', 'id'])
            ->withTimestamps();
    }

    public function inviters(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'invites',
            'sender',
            'receiver'
        )
            ->using(Invite::class)
            ->as('inviters')
            ->withPivot(['id', 'page_id'])
            ->withTimestamps();
    }

    public function invitersOne(): HasMany
    {
        return $this->hasMany(Invite::class, 'sender');
    }

    public function invitees(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'invites',
            'receiver',
            'sender'
        )
            ->using(Invite::class)
            ->as('invitee')
            ->withPivot(['id', 'page_id'])
            ->withTimestamps();
    }

    public function inviteesOne(): HasMany
    {
        return $this->hasMany(Invite::class, 'receiver');
    }

    public function getPeriodReceiverAttribute()
    {
        $duration = now()->diff($this->receiver->created_at)->__serialize();
        if ($duration['y'] > 0)
            return $duration['y'] . ' year(s)';
        elseif ($duration['m'] > 0)
            return $duration['m'] . ' month(s)';
        elseif ($duration['d'] % 7 > 0)
            return $duration['d'] % 7 . ' week(s)';
        elseif ($duration['d'] > 0)
            return $duration['d'] . ' day(s)';
        elseif ($duration['h'] > 0)
            return $duration['h'] . ' hour(s)';
        elseif ($duration['i'] > 0)
            return $duration['i'] . ' minute(s)';
        else
            return $duration['s'] . ' second(s)';
    }

    public function getPeriodSenderAttribute()
    {
        $duration = now()->diff($this->sender->created_at)->__serialize();
        if ($duration['y'] > 0)
            return $duration['y'] . ' year(s)';
        elseif ($duration['m'] > 0)
            return $duration['m'] . ' month(s)';
        elseif ($duration['d'] % 7 > 0)
            return $duration['d'] % 7 . ' week(s)';
        elseif ($duration['d'] > 0)
            return $duration['d'] . ' day(s)';
        elseif ($duration['h'] > 0)
            return $duration['h'] . ' hour(s)';
        elseif ($duration['i'] > 0)
            return $duration['i'] . ' minute(s)';
        else
            return $duration['s'] . ' second(s)';
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')->singleFile();
    }
}
