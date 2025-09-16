<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Channel
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $avatar
 * @property string|null $banner
 * @property int $subscribers_count
 * @property int $videos_count
 * @property bool $is_verified
 * @property bool $allow_nsfw
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Video> $videos
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $subscribers
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Channel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereAllowNsfw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereSubscribersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Channel whereVideosCount($value)
 * @method static \Database\Factories\ChannelFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Channel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'avatar',
        'banner',
        'allow_nsfw',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'allow_nsfw' => 'boolean',
        'subscribers_count' => 'integer',
        'videos_count' => 'integer',
    ];

    /**
     * Get the user that owns the channel.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the videos for the channel.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get the subscriptions to this channel.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the subscribers of this channel.
     */
    public function subscribers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Subscription::class, 'channel_id', 'id', 'id', 'user_id');
    }

    /**
     * Get the published videos for the channel.
     */
    public function publishedVideos(): HasMany
    {
        return $this->videos()->where('is_published', true);
    }

    /**
     * Scope a query to only include verified channels.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}