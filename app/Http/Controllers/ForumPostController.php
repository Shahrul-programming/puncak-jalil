<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumPost;

class ForumPostController extends Controller
{
    public function index()
    {
        $forumPosts = ForumPost::all();
        return view('forum_posts.index', compact('forumPosts'));
    }
    public function create()
    {
        return view('forum_posts.create');
    }
    public function store(Request $request)
    {
        // Simpan forum post baru
    }
    public function edit($id)
    {
        $forumPost = ForumPost::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumPost->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        return view('forum_posts.edit', compact('forumPost'));
    }
    public function update(Request $request, $id)
    {
        // Kemaskini forum post
    }
    public function destroy($id)
    {
        $forumPost = ForumPost::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumPost->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        $forumPost->delete();
        return redirect()->route('forum-posts.index')->with('success', 'Forum post dipadam.');
    }
}
