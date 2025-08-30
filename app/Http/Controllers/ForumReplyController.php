<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumReply;

class ForumReplyController extends Controller
{
    public function index()
    {
        $forumReplies = ForumReply::all();
        return view('forum_replies.index', compact('forumReplies'));
    }
    public function create()
    {
        return view('forum_replies.create');
    }
    public function store(Request $request)
    {
        // Simpan forum reply baru
    }
    public function edit($id)
    {
        $forumReply = ForumReply::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumReply->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        return view('forum_replies.edit', compact('forumReply'));
    }
    public function update(Request $request, $id)
    {
        // Kemaskini forum reply
    }
    public function destroy($id)
    {
        $forumReply = ForumReply::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumReply->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        $forumReply->delete();
        return redirect()->route('forum-replies.index')->with('success', 'Forum reply dipadam.');
    }
}
