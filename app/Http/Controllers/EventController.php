<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }
    public function create()
    {
        return view('events.create');
    }
    public function store(Request $request)
    {
        // Simpan event baru
    }
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $event->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        return view('events.edit', compact('event'));
    }
    public function update(Request $request, $id)
    {
        // Kemaskini event
    }
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();
        if ($user->role !== 'admin' && $event->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event dipadam.');
    }
}
