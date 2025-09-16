<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\VideoLike
 *
 * @property int $id
 * @property int $user_id
 * @property int $video_id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Video $video
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|VideoLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoLike query()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoLike whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoLike whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VideoLike whereVideoId($value)
 * @method static \Database\Factories\VideoLikeFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class VideoLike extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'video_id',
        'type',
    ];

    /**
     * Get the user that owns the like.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the video that is liked.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Scope a query to only include likes.
     */
    public function scopeLikes($query)
    {
        return $query->where('type', 'like');
    }

    /**
     * Scope a query to only include dislikes.
     */
    public function scopeDislikes($query)
    {
        return $query->where('type', 'dislike');
    }
}