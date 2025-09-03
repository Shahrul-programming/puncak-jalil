<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumReply;
use App\Models\ForumPost;
use App\Models\User;
use App\Notifications\ForumReplyReceived;

class ForumReplyController extends Controller
{
    public function store(Request $request, ForumPost $forumPost)
    {
        if ($forumPost->is_locked) {
            return back()->with('error', 'Post ini telah dikunci. Balasan tidak dibenarkan.');
        }

        $request->validate([
            'content' => 'required|string|min:10'
        ]);

        $reply = ForumReply::create([
            'post_id' => $forumPost->id,
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        // Send notification to post author and admins
        $postAuthor = $forumPost->user;
        $admins = User::where('role', 'admin')->get();
        $notifiableUsers = collect([$postAuthor])->merge($admins)->filter();

        foreach ($notifiableUsers as $user) {
            if ($user && $user->id !== Auth::id()) {
                $user->notify(new ForumReplyReceived($reply, $forumPost, Auth::user()));
            }
        }

        return back()->with('success', 'Balasan berjaya ditambah!')->withFragment('replies');
    }

    public function edit(ForumReply $forumReply)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumReply->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        if ($forumReply->post->is_locked && $user->role !== 'admin') {
            abort(403, 'Post ini telah dikunci dan balasan tidak boleh diedit.');
        }

        return view('forum_replies.edit', compact('forumReply'));
    }

    public function update(Request $request, ForumReply $forumReply)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumReply->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        if ($forumReply->post->is_locked && $user->role !== 'admin') {
            abort(403, 'Post ini telah dikunci dan balasan tidak boleh diedit.');
        }

        $request->validate([
            'content' => 'required|string|min:10'
        ]);

        $forumReply->update([
            'content' => $request->content
        ]);

        return redirect()->route('forum-posts.show', $forumReply->post)
                         ->with('success', 'Balasan berjaya dikemaskini!')
                         ->withFragment('reply-' . $forumReply->id);
    }

    public function destroy(ForumReply $forumReply)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumReply->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $postId = $forumReply->post_id;
        $forumReply->delete();
        
        return redirect()->route('forum-posts.show', $postId)
                         ->with('success', 'Balasan berjaya dipadam!');
    }
}
