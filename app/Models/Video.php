<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Video
 *
 * @property int $id
 * @property int $channel_id
 * @property string $title
 * @property string|null $description
 * @property string $video_path
 * @property string|null $thumbnail
 * @property int $duration
 * @property int $views_count
 * @property int $likes_count
 * @property int $dislikes_count
 * @property int $comments_count
 * @property bool $is_nsfw
 * @property bool $is_published
 * @property string $visibility
 * @property array|null $tags
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Channel $channel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VideoLike> $likes
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Video newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video query()
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereCommentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDislikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereIsNsfw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereVideoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereViewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video sfw()
 * @method static \Illuminate\Database\Eloquent\Builder|Video nsfw()
 * @method static \Illuminate\Database\Eloquent\Builder|Video published()
 * @method static \Database\Factories\VideoFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'channel_id',
        'title',
        'description',
        'video_path',
        'thumbnail',
        'duration',
        'is_nsfw',
        'is_published',
        'visibility',
        'tags',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_nsfw' => 'boolean',
        'is_published' => 'boolean',
        'duration' => 'integer',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'dislikes_count' => 'integer',
        'comments_count' => 'integer',
        'tags' => 'array',
    ];

    /**
     * Get the channel that owns the video.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Get the comments for the video.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes for the video.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(VideoLike::class);
    }

    /**
     * Scope a query to only include SFW videos.
     */
    public function scopeSfw($query)
    {
        return $query->where('is_nsfw', false);
    }

    /**
     * Scope a query to only include NSFW videos.
     */
    public function scopeNsfw($query)
    {
        return $query->where('is_nsfw', true);
    }

    /**
     * Scope a query to only include published videos.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get the formatted views count.
     */
    public function getFormattedViewsAttribute(): string
    {
        if ($this->views_count >= 1000000) {
            return round($this->views_count / 1000000, 1) . 'M';
        }

        if ($this->views_count >= 1000) {
            return round($this->views_count / 1000, 1) . 'K';
        }

        return (string) $this->views_count;
    }
}