import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
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

interface Channel {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    subscribers_count: number;
    videos_count: number;
    is_verified: boolean;
    user: {
        name: string;
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
    trendingChannels: Channel[];
    stats: {
        total_videos: number;
        sfw_videos: number;
        nsfw_videos: number;
        total_channels: number;
    };
    filters: {
        content: string;
        search: string;
        sort: string;
    };
    [key: string]: unknown;
}

export default function Welcome({ videos, trendingChannels, stats, filters }: Props) {
    const [searchQuery, setSearchQuery] = useState(filters.search);
    const [contentFilter, setContentFilter] = useState(filters.content);
    const [sortBy, setSortBy] = useState(filters.sort);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/', { 
            search: searchQuery, 
            content: contentFilter, 
            sort: sortBy 
        }, { 
            preserveState: true 
        });
    };

    const handleFilterChange = (newFilter: string) => {
        setContentFilter(newFilter);
        router.get('/', { 
            search: searchQuery, 
            content: newFilter, 
            sort: sortBy 
        }, { 
            preserveState: true 
        });
    };

    const handleSortChange = (newSort: string) => {
        setSortBy(newSort);
        router.get('/', { 
            search: searchQuery, 
            content: contentFilter, 
            sort: newSort 
        }, { 
            preserveState: true 
        });
    };

    return (
        <>
            <Head title="VidStream - Share Your World" />
            
            <div className="min-h-screen bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900">
                {/* Header */}
                <header className="border-b border-white/10 backdrop-blur-sm bg-black/20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center h-16">
                            <div className="flex items-center space-x-4">
                                <h1 className="text-2xl font-bold text-white">
                                    🎬 <span className="bg-gradient-to-r from-pink-400 to-purple-400 bg-clip-text text-transparent">VidStream</span>
                                </h1>
                            </div>
                            
                            <div className="flex items-center space-x-4">
                                <Link
                                    href="/login"
                                    className="text-white hover:text-pink-300 transition-colors"
                                >
                                    Sign In
                                </Link>
                                <Button
                                    asChild
                                    className="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white border-0"
                                >
                                    <Link href="/register">Join Now</Link>
                                </Button>
                            </div>
                        </div>
                    </div>
                </header>

                {/* Hero Section */}
                <section className="py-20 text-center">
                    <div className="max-w-4xl mx-auto px-4">
                        <h2 className="text-5xl font-extrabold text-white mb-6 leading-tight">
                            Share Your World with
                            <span className="block bg-gradient-to-r from-pink-400 via-purple-400 to-blue-400 bg-clip-text text-transparent">
                                Unlimited Creativity 🌟
                            </span>
                        </h2>
                        <p className="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                            Upload, watch, and discover amazing videos from creators worldwide. 
                            Express yourself freely in our vibrant community of storytellers.
                        </p>
                        
                        {/* Stats */}
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
                            <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                                <div className="text-3xl font-bold text-pink-300">{stats.total_videos.toLocaleString()}</div>
                                <div className="text-blue-100">Videos</div>
                            </div>
                            <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                                <div className="text-3xl font-bold text-purple-300">{stats.total_channels.toLocaleString()}</div>
                                <div className="text-blue-100">Creators</div>
                            </div>
                            <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                                <div className="text-3xl font-bold text-blue-300">{stats.sfw_videos.toLocaleString()}</div>
                                <div className="text-blue-100">SFW Content</div>
                            </div>
                            <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                                <div className="text-3xl font-bold text-indigo-300">{stats.nsfw_videos.toLocaleString()}</div>
                                <div className="text-blue-100">18+ Content</div>
                            </div>
                        </div>

                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <Button
                                asChild
                                size="lg"
                                className="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white border-0 text-lg px-8 py-4"
                            >
                                <Link href="/register">🚀 Start Creating</Link>
                            </Button>
                            <Button
                                asChild
                                size="lg"
                                variant="outline"
                                className="border-white/30 text-white hover:bg-white/10 backdrop-blur-sm text-lg px-8 py-4"
                            >
                                <Link href="/videos">🎥 Explore Videos</Link>
                            </Button>
                        </div>
                    </div>
                </section>

                {/* Search & Filters */}
                <section className="py-8 bg-black/20 backdrop-blur-sm">
                    <div className="max-w-6xl mx-auto px-4">
                        <form onSubmit={handleSearch} className="flex flex-col lg:flex-row gap-4 items-center">
                            <div className="flex-1 relative">
                                <input
                                    type="text"
                                    placeholder="🔍 Search videos, channels, topics..."
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="w-full px-6 py-4 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent backdrop-blur-sm text-lg"
                                />
                            </div>
                            
                            <div className="flex gap-2">
                                <select
                                    value={contentFilter}
                                    onChange={(e) => handleFilterChange(e.target.value)}
                                    className="px-4 py-4 rounded-xl bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-pink-400 backdrop-blur-sm"
                                >
                                    <option value="sfw" className="bg-gray-800">🌟 Safe Content</option>
                                    <option value="nsfw" className="bg-gray-800">🔞 Adult Content</option>
                                </select>
                                
                                <select
                                    value={sortBy}
                                    onChange={(e) => handleSortChange(e.target.value)}
                                    className="px-4 py-4 rounded-xl bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-pink-400 backdrop-blur-sm"
                                >
                                    <option value="latest" className="bg-gray-800">📅 Latest</option>
                                    <option value="popular" className="bg-gray-800">🔥 Popular</option>
                                    <option value="trending" className="bg-gray-800">📈 Trending</option>
                                    <option value="liked" className="bg-gray-800">❤️ Most Liked</option>
                                </select>
                            </div>
                            
                            <Button
                                type="submit"
                                className="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white border-0 px-8 py-4"
                            >
                                Search
                            </Button>
                        </form>
                    </div>
                </section>

                {/* Featured Videos */}
                <section className="py-12">
                    <div className="max-w-7xl mx-auto px-4">
                        <div className="flex items-center justify-between mb-8">
                            <h3 className="text-3xl font-bold text-white flex items-center gap-2">
                                🎬 Featured Videos
                            </h3>
                            <Link 
                                href="/videos"
                                className="text-pink-300 hover:text-pink-200 transition-colors"
                            >
                                View All →
                            </Link>
                        </div>
                        
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            {videos.data.map((video) => (
                                <Link
                                    key={video.id}
                                    href={`/videos/${video.id}`}
                                    className="group block"
                                >
                                    <div className="bg-white/10 backdrop-blur-sm rounded-xl overflow-hidden border border-white/20 hover:border-pink-400/50 transition-all duration-300 hover:scale-105">
                                        <div className="aspect-video bg-gradient-to-br from-purple-600 to-pink-600 relative flex items-center justify-center">
                                            {video.thumbnail ? (
                                                <img 
                                                    src={`/storage/${video.thumbnail}`}
                                                    alt={video.title}
                                                    className="w-full h-full object-cover"
                                                />
                                            ) : (
                                                <div className="text-6xl">🎥</div>
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
                                            <h4 className="font-semibold text-white mb-2 line-clamp-2 group-hover:text-pink-300 transition-colors">
                                                {video.title}
                                            </h4>
                                            <div className="flex items-center gap-2 text-sm text-blue-200">
                                                <span>{video.channel.name}</span>
                                                {video.channel.is_verified && <span>✓</span>}
                                            </div>
                                            <div className="text-xs text-blue-300 mt-1">
                                                {video.formatted_views} views
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            ))}
                        </div>

                        {videos.data.length === 0 && (
                            <div className="text-center py-12">
                                <div className="text-6xl mb-4">📹</div>
                                <h3 className="text-xl text-white mb-2">No videos found</h3>
                                <p className="text-blue-200">
                                    {filters.search ? 'Try a different search term' : 'Be the first to upload a video!'}
                                </p>
                            </div>
                        )}
                    </div>
                </section>

                {/* Trending Channels */}
                {trendingChannels.length > 0 && (
                    <section className="py-12 bg-black/20 backdrop-blur-sm">
                        <div className="max-w-7xl mx-auto px-4">
                            <h3 className="text-3xl font-bold text-white mb-8 flex items-center gap-2">
                                🌟 Trending Creators
                            </h3>
                            
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                {trendingChannels.map((channel) => (
                                    <Link
                                        key={channel.id}
                                        href={`/channels/${channel.slug}`}
                                        className="group block"
                                    >
                                        <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 hover:border-purple-400/50 transition-all duration-300 hover:scale-105 text-center">
                                            <div className="w-16 h-16 bg-gradient-to-br from-pink-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl">
                                                👤
                                            </div>
                                            <h4 className="font-semibold text-white mb-2 flex items-center justify-center gap-1">
                                                {channel.name}
                                                {channel.is_verified && <span className="text-blue-400">✓</span>}
                                            </h4>
                                            <div className="text-sm text-blue-200 mb-1">
                                                {channel.subscribers_count.toLocaleString()} subscribers
                                            </div>
                                            <div className="text-xs text-blue-300">
                                                {channel.videos_count} videos
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </section>
                )}

                {/* Features */}
                <section className="py-20">
                    <div className="max-w-6xl mx-auto px-4">
                        <h3 className="text-4xl font-bold text-white text-center mb-12">
                            Why Choose VidStream? ✨
                        </h3>
                        
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div className="text-center">
                                <div className="text-6xl mb-4">🎬</div>
                                <h4 className="text-xl font-bold text-white mb-4">Unlimited Uploads</h4>
                                <p className="text-blue-100">Share your creativity with no limits. Upload videos of any length and build your audience.</p>
                            </div>
                            
                            <div className="text-center">
                                <div className="text-6xl mb-4">🔒</div>
                                <h4 className="text-xl font-bold text-white mb-4">Safe & Secure</h4>
                                <p className="text-blue-100">Advanced content filtering and privacy controls keep your experience safe and enjoyable.</p>
                            </div>
                            
                            <div className="text-center">
                                <div className="text-6xl mb-4">💰</div>
                                <h4 className="text-xl font-bold text-white mb-4">Monetization Ready</h4>
                                <p className="text-blue-100">Turn your passion into profit with our creator-friendly monetization features.</p>
                            </div>
                            
                            <div className="text-center">
                                <div className="text-6xl mb-4">🌍</div>
                                <h4 className="text-xl font-bold text-white mb-4">Global Community</h4>
                                <p className="text-blue-100">Connect with viewers and creators from around the world in our vibrant community.</p>
                            </div>
                            
                            <div className="text-center">
                                <div className="text-6xl mb-4">📱</div>
                                <h4 className="text-xl font-bold text-white mb-4">Mobile Optimized</h4>
                                <p className="text-blue-100">Enjoy seamless video streaming and uploading across all your devices.</p>
                            </div>
                            
                            <div className="text-center">
                                <div className="text-6xl mb-4">🎯</div>
                                <h4 className="text-xl font-bold text-white mb-4">Smart Discovery</h4>
                                <p className="text-blue-100">Advanced algorithms help you discover content you'll love and grow your audience.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* CTA Section */}
                <section className="py-20 bg-gradient-to-r from-pink-600 to-purple-700">
                    <div className="max-w-4xl mx-auto px-4 text-center">
                        <h3 className="text-4xl font-bold text-white mb-6">
                            Ready to Share Your Story? 🚀
                        </h3>
                        <p className="text-xl text-pink-100 mb-8">
                            Join millions of creators who have found their voice on VidStream
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <Button
                                asChild
                                size="lg"
                                className="bg-white text-purple-600 hover:bg-gray-100 border-0 text-lg px-8 py-4 font-semibold"
                            >
                                <Link href="/register">Create Account Free</Link>
                            </Button>
                            <Button
                                asChild
                                size="lg"
                                variant="outline"
                                className="border-white text-white hover:bg-white/10 backdrop-blur-sm text-lg px-8 py-4"
                            >
                                <Link href="/videos">Browse Videos</Link>
                            </Button>
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="py-12 bg-black/40 backdrop-blur-sm border-t border-white/10">
                    <div className="max-w-6xl mx-auto px-4">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div>
                                <h4 className="text-xl font-bold text-white mb-4">🎬 VidStream</h4>
                                <p className="text-blue-200 text-sm">
                                    The ultimate platform for video creators and viewers worldwide.
                                </p>
                            </div>
                            <div>
                                <h5 className="font-semibold text-white mb-4">For Creators</h5>
                                <ul className="space-y-2 text-sm text-blue-200">
                                    <li><Link href="/register" className="hover:text-pink-300">Start Creating</Link></li>
                                    <li><Link href="/channels" className="hover:text-pink-300">Browse Channels</Link></li>
                                </ul>
                            </div>
                            <div>
                                <h5 className="font-semibold text-white mb-4">For Viewers</h5>
                                <ul className="space-y-2 text-sm text-blue-200">
                                    <li><Link href="/videos" className="hover:text-pink-300">Watch Videos</Link></li>
                                    <li><Link href="/login" className="hover:text-pink-300">Sign In</Link></li>
                                </ul>
                            </div>
                            <div>
                                <h5 className="font-semibold text-white mb-4">Community</h5>
                                <ul className="space-y-2 text-sm text-blue-200">
                                    <li>📧 Support</li>
                                    <li>📱 Mobile Apps</li>
                                    <li>🌟 Premium</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div className="border-t border-white/10 mt-8 pt-8 text-center">
                            <p className="text-blue-200 text-sm">
                                © 2024 VidStream. Made with ❤️ for creators worldwide.
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}