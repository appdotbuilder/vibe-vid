<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $video = Video::findOrFail($request->video_id);

        $comment = Comment::create([
            'video_id' => $video->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        // Update video comment count
        $video->increment('comments_count');

        $comment->load(['user']);

        return redirect()->back()
            ->with('success', 'Comment posted successfully!');
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if user owns this comment
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'You can only edit your own comments.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return redirect()->back()
            ->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        // Check if user owns this comment
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'You can only delete your own comments.');
        }

        $video = $comment->video;
        $comment->delete();

        // Update video comment count
        $video->decrement('comments_count');

        return redirect()->back()
            ->with('success', 'Comment deleted successfully!');
    }
}