import React from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem, type SharedData } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard() {
    const { auth, quote } = usePage<SharedData>().props;
    const user = auth.user;

    const stats = [
        { title: 'My Videos', value: '0', icon: '🎬', href: '/videos', color: 'from-blue-500 to-purple-600' },
        { title: 'My Channels', value: '0', icon: '📺', href: '/channels', color: 'from-purple-500 to-pink-600' },
        { title: 'Subscriptions', value: '0', icon: '❤️', href: '/subscriptions', color: 'from-pink-500 to-red-500' },
        { title: 'Total Views', value: '0', icon: '👀', href: '#', color: 'from-green-500 to-blue-500' },
    ];

    const quickActions = [
        {
            title: 'Upload Video',
            description: 'Share your latest creation with the world',
            icon: '🎥',
            href: '/videos/create',
            color: 'from-blue-600 to-purple-700',
        },
        {
            title: 'Create Channel',
            description: 'Start building your brand and audience',
            icon: '📢',
            href: '/channels/create',
            color: 'from-purple-600 to-pink-700',
        },
        {
            title: 'Browse Videos',
            description: 'Discover trending content and creators',
            icon: '🔍',
            href: '/videos',
            color: 'from-pink-600 to-red-700',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-8 rounded-xl p-6">
                {/* Welcome Section */}
                <div className="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white">
                    <div className="flex items-center justify-between">
                        <div>
                            <h1 className="text-3xl font-bold mb-2">
                                Welcome back, {user.name}! 👋
                            </h1>
                            <p className="text-blue-100 text-lg mb-4">
                                Ready to create something amazing today?
                            </p>
                            <div className="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 max-w-2xl">
                                <div className="flex items-start gap-3">
                                    <div className="text-2xl">💡</div>
                                    <div>
                                        <p className="text-white/90 italic mb-1">"{quote.message}"</p>
                                        <p className="text-blue-200 text-sm">— {quote.author}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="text-6xl opacity-20">
                            🎬
                        </div>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {stats.map((stat, index) => (
                        <Link key={index} href={stat.href} className="group">
                            <Card className="border-0 bg-gradient-to-r from-white/5 to-white/10 backdrop-blur-sm hover:from-white/10 hover:to-white/20 transition-all duration-300 group-hover:scale-105">
                                <CardContent className="p-6">
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <p className="text-sm font-medium text-muted-foreground">
                                                {stat.title}
                                            </p>
                                            <p className="text-3xl font-bold">{stat.value}</p>
                                        </div>
                                        <div className={`text-4xl p-3 rounded-xl bg-gradient-to-r ${stat.color} text-white`}>
                                            {stat.icon}
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </Link>
                    ))}
                </div>

                {/* Quick Actions */}
                <div>
                    <h2 className="text-2xl font-bold mb-6 flex items-center gap-2">
                        🚀 Quick Actions
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {quickActions.map((action, index) => (
                            <Link key={index} href={action.href} className="group">
                                <Card className="border-0 bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-sm hover:from-white/10 hover:to-white/20 transition-all duration-300 group-hover:scale-105 group-hover:shadow-xl">
                                    <CardHeader className="pb-3">
                                        <div className={`w-12 h-12 rounded-xl bg-gradient-to-r ${action.color} flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform duration-300`}>
                                            {action.icon}
                                        </div>
                                        <CardTitle className="text-xl group-hover:text-blue-400 transition-colors duration-300">
                                            {action.title}
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent className="pt-0">
                                        <p className="text-muted-foreground">
                                            {action.description}
                                        </p>
                                    </CardContent>
                                </Card>
                            </Link>
                        ))}
                    </div>
                </div>

                {/* Recent Activity */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Recent Videos */}
                    <Card className="border-0 bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-sm">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                🎬 Recent Videos
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="text-center py-8">
                                <div className="text-4xl mb-3">📹</div>
                                <h3 className="font-semibold mb-2">No videos yet</h3>
                                <p className="text-muted-foreground mb-4">
                                    Share your first video to get started!
                                </p>
                                <Button asChild className="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700">
                                    <Link href="/videos/create">
                                        Upload Video
                                    </Link>
                                </Button>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Recent Activity */}
                    <Card className="border-0 bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-sm">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                📊 Activity Feed
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-4">
                                <div className="flex items-center gap-3 p-3 bg-white/5 rounded-lg">
                                    <div className="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center">
                                        ✨
                                    </div>
                                    <div>
                                        <p className="font-medium">Welcome to VidStream!</p>
                                        <p className="text-sm text-muted-foreground">
                                            Start by creating your first channel
                                        </p>
                                    </div>
                                </div>
                                
                                <div className="text-center py-4">
                                    <p className="text-muted-foreground text-sm">
                                        Your activity will appear here as you use the platform
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Getting Started Tips */}
                <Card className="border-0 bg-gradient-to-r from-amber-500/10 to-orange-500/10 backdrop-blur-sm border-amber-200/20">
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-amber-400">
                            💡 Getting Started Tips
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div className="flex items-start gap-3">
                                <div className="text-2xl">1️⃣</div>
                                <div>
                                    <h4 className="font-semibold mb-1">Create Your Channel</h4>
                                    <p className="text-sm text-muted-foreground">
                                        Set up your brand and start building your audience
                                    </p>
                                </div>
                            </div>
                            
                            <div className="flex items-start gap-3">
                                <div className="text-2xl">2️⃣</div>
                                <div>
                                    <h4 className="font-semibold mb-1">Upload Your First Video</h4>
                                    <p className="text-sm text-muted-foreground">
                                        Share your content with the world
                                    </p>
                                </div>
                            </div>
                            
                            <div className="flex items-start gap-3">
                                <div className="text-2xl">3️⃣</div>
                                <div>
                                    <h4 className="font-semibold mb-1">Engage with Community</h4>
                                    <p className="text-sm text-muted-foreground">
                                        Like, comment, and subscribe to other creators
                                    </p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}