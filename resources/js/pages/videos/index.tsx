import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';

interface Video {
    id: number;
    title: string;
    description: string | null;
    thumbnail: string | null;
    duration: number;
    views_count: number;
    formatted_views: string;
    formatted_duration: string;
    is_nsfw: boolean;
    created_at: string;
    channel: {
        id: number;
        name: string;
        slug: string;
        is_verified: boolean;
        user: {
            id: number;
            name: string;
        };
    };
}

interface Props {
    videos: {
        data: Video[];
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        meta: {
            current_page: number;
            last_page: number;
            per_page: number;
            total: number;
        };
    };
    search: string;
    nsfw: string;
    [key: string]: unknown;
}

export default function VideosIndex({ videos, search, nsfw }: Props) {
    const [searchQuery, setSearchQuery] = useState(search);
    const [contentFilter, setContentFilter] = useState(nsfw);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/videos', { 
            search: searchQuery, 
            nsfw: contentFilter 
        }, { 
            preserveState: true 
        });
    };

    const handleFilterChange = (newFilter: string) => {
        setContentFilter(newFilter);
        router.get('/videos', { 
            search: searchQuery, 
            nsfw: newFilter 
        }, { 
            preserveState: true 
        });
    };

    return (
        <AppShell>
            <Head title="All Videos - VidStream" />
            
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        🎬 All Videos
                    </h1>
                    
                    <Button asChild className="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white border-0">
                        <Link href="/videos/create">📹 Upload Video</Link>
                    </Button>
                </div>

                {/* Search & Filters */}
                <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <form onSubmit={handleSearch} className="flex flex-col lg:flex-row gap-4 items-center">
                        <div className="flex-1 relative">
                            <input
                                type="text"
                                placeholder="🔍 Search videos..."
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                className="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent"
                            />
                        </div>
                        
                        <div className="flex gap-2">
                            <select
                                value={contentFilter}
                                onChange={(e) => handleFilterChange(e.target.value)}
                                className="px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-400"
                            >
                                <option value="false">🌟 Safe Content</option>
                                <option value="true">🔞 Adult Content</option>
                            </select>
                        </div>
                        
                        <Button
                            type="submit"
                            className="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white border-0 px-6"
                        >
                            Search
                        </Button>
                    </form>
                </div>

                {/* Videos Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    {videos.data.map((video) => (
                        <Link
                            key={video.id}
                            href={`/videos/${video.id}`}
                            className="group block"
                        >
                            <div className="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 hover:border-pink-400 hover:shadow-lg transition-all duration-300 hover:scale-105">
                                <div className="aspect-video bg-gradient-to-br from-purple-600 to-pink-600 relative flex items-center justify-center">
                                    {video.thumbnail ? (
                                        <img 
                                            src={`/storage/${video.thumbnail}`}
                                            alt={video.title}
                                            className="w-full h-full object-cover"
                                        />
                                    ) : (
                                        <div className="text-4xl text-white">🎥</div>
                                    )}
                                    <div className="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                        {video.formatted_duration}
                                    </div>
                                    {video.is_nsfw && (
                                        <div className="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                            18+
                                        </div>
                                    )}
                                </div>
                                <div className="p-4">
                                    <h3 className="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-pink-500 transition-colors">
                                        {video.title}
                                    </h3>
                                    <div className="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        <span>{video.channel.name}</span>
                                        {video.channel.is_verified && <span className="text-blue-500">✓</span>}
                                    </div>
                                    <div className="text-xs text-gray-500 dark:text-gray-500">
                                        {video.formatted_views} views • {new Date(video.created_at).toLocaleDateString()}
                                    </div>
                                </div>
                            </div>
                        </Link>
                    ))}
                </div>

                {videos.data.length === 0 && (
                    <div className="text-center py-12">
                        <div className="text-6xl mb-4">📹</div>
                        <h3 className="text-xl text-gray-900 dark:text-white mb-2">No videos found</h3>
                        <p className="text-gray-600 dark:text-gray-400 mb-6">
                            {search ? 'Try a different search term' : 'Be the first to upload a video!'}
                        </p>
                        <Button asChild className="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white border-0">
                            <Link href="/videos/create">📹 Upload First Video</Link>
                        </Button>
                    </div>
                )}

                {/* Pagination */}
                {videos.data.length > 0 && (
                    <div className="flex justify-center">
                        <div className="flex items-center space-x-2">
                            {videos.links.map((link, index: number) => 
                                link.url ? (
                                    <Link
                                        key={index}
                                        href={link.url}
                                        className={`px-3 py-2 rounded-md text-sm font-medium ${
                                            link.active
                                                ? 'bg-pink-500 text-white'
                                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'
                                        }`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ) : (
                                    <span
                                        key={index}
                                        className="px-3 py-2 rounded-md text-sm font-medium opacity-50 cursor-not-allowed bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300"
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                )
                            )}
                        </div>
                    </div>
                )}
            </div>
        </AppShell>
    );
}