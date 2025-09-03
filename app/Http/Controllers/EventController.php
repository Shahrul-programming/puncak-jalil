<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\NewEventCreated;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::with('user');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }
        
        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Date filter
        if ($request->filled('date_filter')) {
            $today = Carbon::today();
            switch ($request->date_filter) {
                case 'upcoming':
                    $query->where('date', '>=', Carbon::now());
                    break;
                case 'today':
                    $query->whereDate('date', $today);
                    break;
                case 'past':
                    $query->where('date', '<', Carbon::now());
                    break;
                case 'this_week':
                    $query->whereBetween('date', [$today, $today->copy()->addWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('date', $today->month)
                          ->whereYear('date', $today->year);
                    break;
            }
        }
        
        // Order by date (upcoming first, then by date)
        $query->orderByRaw("CASE WHEN date >= ? THEN 0 ELSE 1 END", [Carbon::today()])
              ->orderBy('date');
        
        $events = $query->paginate(12)->withQueryString();
        
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'date' => 'required|date|after_or_equal:today',
            'location' => 'required|string|max:500',
            'type' => 'required|in:event,notis,meeting,activity'
        ]);

        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'type' => $request->type,
        ]);

        // Send notification to all users about new event
        $users = User::where('id', '!=', Auth::id())->get();
        
        foreach ($users as $user) {
            $user->notify(new NewEventCreated($event, Auth::user()));
        }

        return redirect()->route('events.index')->with('success', 'Acara berjaya ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with('user')->findOrFail($id);
        
        // Get related events (same type, future events, excluding current)
        $relatedEvents = Event::where('type', $event->type)
            ->where('id', '!=', $event->id)
            ->where('date', '>=', Carbon::now())
            ->orderBy('date')
            ->limit(3)
            ->get();
        
        return view('events.show', compact('event', 'relatedEvents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $event->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $event->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'date' => 'required|date',
            'location' => 'required|string|max:500',
            'type' => 'required|in:event,notis,meeting,activity'
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'type' => $request->type,
        ]);

        return redirect()->route('events.index')->with('success', 'Acara berjaya dikemaskini!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'admin' && $event->user_id !== $user->id) {
            abort(403, 'Akses tidak dibenarkan.');
        }
        
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Acara berjaya dipadam!');
    }

    /**
     * Display calendar view of events
     */
    public function calendar()
    {
        $events = Event::all(['id', 'title', 'date', 'type']);
        return view('events.calendar', compact('events'));
    }
}
