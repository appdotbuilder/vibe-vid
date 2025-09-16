import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface Video {
    id: number;
    title: string;
    description: string | null;
    video_path: string;
    thumbnail: string | null;
    duration: number;
    views_count: number;
    likes_count: number;
    dislikes_count: number;
    comments_count: number;
    formatted_views: string;
    formatted_duration: string;
    is_nsfw: boolean;
    created_at: string;
    channel: {
        id: number;
        name: string;
        slug: string;
        is_verified: boolean;
        subscribers_count: number;
        user: {
            id: number;
            name: string;
        };
    };
    comments: Array<{
        id: number;
        content: string;
        likes_count: number;
        created_at: string;
        user: {
            id: number;
            name: string;
        };
        replies: Array<{
            id: number;
            content: string;
            likes_count: number;
            created_at: string;
            user: {
                id: number;
                name: string;
            };
        }>;
    }>;
}

interface Props {
    video: Video;
    relatedVideos: Array<{
        id: number;
        title: string;
        thumbnail: string | null;
        formatted_views: string;
        channel: {
            name: string;
        };
    }>;
    isSubscribed: boolean;
    userLike: string | null;
    [key: string]: unknown;
}

export default function VideoShow({ video, relatedVideos, isSubscribed, userLike }: Props) {
    const handleLike = (type: 'like' | 'dislike') => {
        router.post('/video-likes', {
            video_id: video.id,
            type: type
        }, {
            preserveState: true,
            preserveScroll: true
        });
    };

    const handleSubscribe = () => {
        if (isSubscribed) {
            router.visit('/subscriptions', {
                method: 'delete',
                data: { channel_id: video.channel.id },
                preserveState: true,
                preserveScroll: true
            });
        } else {
            router.post('/subscriptions', {
                channel_id: video.channel.id
            }, {
                preserveState: true,
                preserveScroll: true
            });
        }
    };

    const handleComment = (e: React.FormEvent) => {
        e.preventDefault();
        const form = e.target as HTMLFormElement;
        const formData = new FormData(form);
        
        router.post('/comments', {
            video_id: video.id,
            content: formData.get('content')
        }, {
            preserveState: true,
            onSuccess: () => form.reset()
        });
    };

    return (
        <AppShell>
            <Head title={`${video.title} - VidStream`} />
            
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Main Content */}
                <div className="lg:col-span-2 space-y-6">
                    {/* Video Player */}
                    <div className="bg-black rounded-lg overflow-hidden aspect-video">
                        <video 
                            controls 
                            className="w-full h-full"
                            poster={video.thumbnail ? `/storage/${video.thumbnail}` : undefined}
                        >
                            <source src={`/storage/${video.video_path}`} type="video/mp4" />
                            Your browser does not support the video tag.
                        </video>
                    </div>

                    {/* Video Info */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div className="mb-4">
                            <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {video.title}
                                {video.is_nsfw && (
                                    <span className="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded">18+</span>
                                )}
                            </h1>
                            <div className="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                <span>{video.formatted_views} views</span>
                                <span>•</span>
                                <span>{new Date(video.created_at).toLocaleDateString()}</span>
                            </div>
                        </div>

                        {/* Actions */}
                        <div className="flex items-center justify-between mb-6">
                            <div className="flex items-center gap-4">
                                {/* Like/Dislike */}
                                <div className="flex items-center bg-gray-100 dark:bg-gray-700 rounded-full">
                                    <button
                                        onClick={() => handleLike('like')}
                                        className={`px-4 py-2 rounded-l-full flex items-center gap-2 transition-colors ${
                                            userLike === 'like' 
                                                ? 'bg-pink-500 text-white' 
                                                : 'hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300'
                                        }`}
                                    >
                                        👍 {video.likes_count.toLocaleString()}
                                    </button>
                                    <button
                                        onClick={() => handleLike('dislike')}
                                        className={`px-4 py-2 rounded-r-full flex items-center gap-2 transition-colors ${
                                            userLike === 'dislike' 
                                                ? 'bg-gray-500 text-white' 
                                                : 'hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300'
                                        }`}
                                    >
                                        👎 {video.dislikes_count.toLocaleString()}
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Channel Info */}
                        <div className="flex items-center justify-between">
                            <Link href={`/channels/${video.channel.slug}`} className="flex items-center gap-4 group">
                                <div className="w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {video.channel.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <div className="flex items-center gap-2">
                                        <span className="font-semibold text-gray-900 dark:text-white group-hover:text-pink-500 transition-colors">
                                            {video.channel.name}
                                        </span>
                                        {video.channel.is_verified && <span className="text-blue-500">✓</span>}
                                    </div>
                                    <div className="text-sm text-gray-600 dark:text-gray-400">
                                        {video.channel.subscribers_count.toLocaleString()} subscribers
                                    </div>
                                </div>
                            </Link>

                            <Button
                                onClick={handleSubscribe}
                                className={`px-6 py-2 rounded-full font-semibold transition-all ${
                                    isSubscribed
                                        ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600'
                                        : 'bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white border-0'
                                }`}
                            >
                                {isSubscribed ? '✓ Subscribed' : '➕ Subscribe'}
                            </Button>
                        </div>

                        {/* Description */}
                        {video.description && (
                            <div className="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <p className="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                    {video.description}
                                </p>
                            </div>
                        )}
                    </div>

                    {/* Comments */}
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            💬 Comments ({video.comments_count.toLocaleString()})
                        </h3>

                        {/* Comment Form */}
                        <form onSubmit={handleComment} className="mb-6">
                            <textarea
                                name="content"
                                placeholder="Add a comment..."
                                className="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent resize-none"
                                rows={3}
                                required
                            />
                            <div className="mt-3 flex justify-end">
                                <Button
                                    type="submit"
                                    className="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white border-0"
                                >
                                    Post Comment
                                </Button>
                            </div>
                        </form>

                        {/* Comments List */}
                        <div className="space-y-4">
                            {video.comments.map((comment) => (
                                <div key={comment.id} className="space-y-3">
                                    <div className="flex gap-3">
                                        <div className="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                            {comment.user.name.charAt(0).toUpperCase()}
                                        </div>
                                        <div className="flex-1">
                                            <div className="flex items-center gap-2 mb-1">
                                                <span className="font-medium text-gray-900 dark:text-white text-sm">
                                                    {comment.user.name}
                                                </span>
                                                <span className="text-xs text-gray-500 dark:text-gray-500">
                                                    {new Date(comment.created_at).toLocaleDateString()}
                                                </span>
                                            </div>
                                            <p className="text-gray-700 dark:text-gray-300 text-sm mb-2">
                                                {comment.content}
                                            </p>
                                            <div className="flex items-center gap-4">
                                                <button className="text-xs text-gray-500 hover:text-pink-500 flex items-center gap-1">
                                                    👍 {comment.likes_count}
                                                </button>
                                                <button className="text-xs text-gray-500 hover:text-pink-500">
                                                    Reply
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Replies */}
                                    {comment.replies.map((reply) => (
                                        <div key={reply.id} className="ml-11 flex gap-3">
                                            <div className="w-6 h-6 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                                {reply.user.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div className="flex-1">
                                                <div className="flex items-center gap-2 mb-1">
                                                    <span className="font-medium text-gray-900 dark:text-white text-sm">
                                                        {reply.user.name}
                                                    </span>
                                                    <span className="text-xs text-gray-500 dark:text-gray-500">
                                                        {new Date(reply.created_at).toLocaleDateString()}
                                                    </span>
                                                </div>
                                                <p className="text-gray-700 dark:text-gray-300 text-sm mb-2">
                                                    {reply.content}
                                                </p>
                                                <button className="text-xs text-gray-500 hover:text-pink-500 flex items-center gap-1">
                                                    👍 {reply.likes_count}
                                                </button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Sidebar */}
                <div className="space-y-6">
                    {/* Related Videos */}
                    {relatedVideos.length > 0 && (
                        <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                            <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                🔥 Related Videos
                            </h3>
                            <div className="space-y-4">
                                {relatedVideos.map((relatedVideo) => (
                                    <Link
                                        key={relatedVideo.id}
                                        href={`/videos/${relatedVideo.id}`}
                                        className="flex gap-3 group"
                                    >
                                        <div className="w-24 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded flex items-center justify-center text-white flex-shrink-0">
                                            {relatedVideo.thumbnail ? (
                                                <img 
                                                    src={`/storage/${relatedVideo.thumbnail}`}
                                                    alt={relatedVideo.title}
                                                    className="w-full h-full object-cover rounded"
                                                />
                                            ) : (
                                                <div className="text-lg">🎥</div>
                                            )}
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <h4 className="font-medium text-gray-900 dark:text-white text-sm line-clamp-2 group-hover:text-pink-500 transition-colors">
                                                {relatedVideo.title}
                                            </h4>
                                            <div className="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                {relatedVideo.channel.name}
                                            </div>
                                            <div className="text-xs text-gray-500 dark:text-gray-500">
                                                {relatedVideo.formatted_views} views
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppShell>
    );
}