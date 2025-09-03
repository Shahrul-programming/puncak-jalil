<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumPost;

class ForumPostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        
        $forumPosts = ForumPost::with(['user', 'replies'])
            ->search($search)
            ->category($category)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = ForumPost::getCategories();
        $totalPosts = ForumPost::count();
        $totalReplies = \App\Models\ForumReply::count();

        return view('forum_posts.index', compact('forumPosts', 'categories', 'search', 'category', 'totalPosts', 'totalReplies'));
    }

    public function create()
    {
        $categories = ForumPost::getCategories();
        return view('forum_posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:' . implode(',', array_keys(ForumPost::getCategories()))
        ]);

        ForumPost::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category
        ]);

        return redirect()->route('forum-posts.index')->with('success', 'Post forum berjaya dicipta!');
    }

    public function show(ForumPost $forumPost)
    {
        // Increment views count
        $forumPost->incrementViews();
        
        // Load relationships
        $forumPost->load(['user', 'replies.user']);
        
        // Get related posts from same category
        $relatedPosts = ForumPost::where('category', $forumPost->category)
            ->where('id', '!=', $forumPost->id)
            ->latest()
            ->take(5)
            ->get();

        return view('forum_posts.show', compact('forumPost', 'relatedPosts'));
    }

    public function edit(ForumPost $forumPost)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumPost->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        if ($forumPost->is_locked && $user->role !== 'admin') {
            abort(403, 'Post ini telah dikunci dan tidak boleh diedit.');
        }

        $categories = ForumPost::getCategories();
        return view('forum_posts.edit', compact('forumPost', 'categories'));
    }

    public function update(Request $request, ForumPost $forumPost)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumPost->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        if ($forumPost->is_locked && $user->role !== 'admin') {
            abort(403, 'Post ini telah dikunci dan tidak boleh diedit.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:' . implode(',', array_keys(ForumPost::getCategories()))
        ]);

        $forumPost->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category
        ]);

        return redirect()->route('forum-posts.show', $forumPost)->with('success', 'Post forum berjaya dikemaskini!');
    }

    public function destroy(ForumPost $forumPost)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $forumPost->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $forumPost->delete();
        return redirect()->route('forum-posts.index')->with('success', 'Post forum berjaya dipadam!');
    }

    // Admin functions
    public function pin(ForumPost $forumPost)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $forumPost->update(['is_pinned' => !$forumPost->is_pinned]);
        
        $status = $forumPost->is_pinned ? 'disematkan' : 'dibuka sematan';
        return back()->with('success', "Post berjaya {$status}!");
    }

    public function lock(ForumPost $forumPost)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $forumPost->update(['is_locked' => !$forumPost->is_locked]);
        
        $status = $forumPost->is_locked ? 'dikunci' : 'dibuka kunci';
        return back()->with('success', "Post berjaya {$status}!");
    }
}
