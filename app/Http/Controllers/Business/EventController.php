<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $events = $user->events()->latest()->paginate(10);

        $metrics = [
            'total_events' => $user->events()->count(),
            'upcoming_events' => $user->events()->where('event_date', '>', now())->count(),
            'total_points_awarded' => DB::table('event_participants')
                ->join('events', 'event_participants.event_id', '=', 'events.id')
                ->where('events.business_id', $user->id)
                ->where('event_participants.points_awarded', true)
                ->sum('events.points_reward'),
        ];

        return view('business.events.index', compact('events', 'metrics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('business.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after:now'],
            'points_reward' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ]);

        $data = $request->except('image');
        $data['business_id'] = auth()->id();
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);

        // Notify all employees and customers of this business
        $business = auth()->user();
        $targetUsers = $business->employees->concat($business->customers);
        
        \Illuminate\Support\Facades\Notification::send(
            $targetUsers, 
            new \App\Notifications\NewEventCreated($event)
        );

        return redirect()->route('business.events.index')->with('status', 'Event created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if ($event->business_id !== auth()->id()) {
            abort(403);
        }
        return view('business.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if ($event->business_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after:now'],
            'points_reward' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->image_path);
            }
            $data['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('business.events.index')->with('status', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if ($event->business_id !== auth()->id()) {
            abort(403);
        }

        $event->delete();

        return redirect()->route('business.events.index')->with('status', 'Event deleted successfully!');
    }

    /**
     * Display participants for the event.
     */
    public function participants(Event $event)
    {
        if ($event->business_id !== auth()->id()) {
            abort(403);
        }

        $business = auth()->user();
        
        // Users who officially joined via the app
        $participants = $event->participants()->latest('event_participants.created_at')->get();
        
        // All eligible users who could attend (Employees and Customers of this business)
        $eligibleUsers = $business->employees->concat($business->customers);

        return view('business.events.participants', compact('event', 'participants', 'eligibleUsers'));
    }

    /**
     * Record physical attendance and award points.
     * This works for both pre-joined users and manual additions.
     */
    public function recordAttendance(Request $request, Event $event)
    {
        if ($event->business_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->user_id;
        $user = \App\Models\User::findOrFail($userId);
        $business = auth()->user();
        $points = $event->points_reward;

        // Eligibility check
        $isEligible = ($user->business_id == $business->id) || ($user->hasRole('employee') && $user->business_id == $business->id);
        if (!$isEligible) {
            return back()->with('error', 'This user is not associated with your business.');
        }

        if ($points <= 0) {
            return back()->with('error', 'This event has no point reward configured.');
        }

        if ($business->points < $points) {
            return back()->with('error', "Insufficient business points balance to award {$points} points.");
        }

        // Check if already awarded
        $participant = $event->participants()->where('user_id', $userId)->first();
        if ($participant && $participant->pivot->points_awarded) {
            return back()->with('info', "Points have already been awarded to {$user->name} for this event.");
        }

        DB::beginTransaction();
        try {
            // Deduct from business
            $business->decrement('points', $points);
            
            // Add to user
            $user->increment('points', $points);

            if ($participant) {
                // Update existing join record
                $event->participants()->updateExistingPivot($userId, [
                    'status' => 'going',
                    'attended_at' => now(),
                    'points_awarded' => true,
                    'awarded_at' => now(),
                ]);
            } else {
                // Manually attach and mark as attended/awarded
                $event->participants()->attach($userId, [
                    'status' => 'going',
                    'attended_at' => now(),
                    'points_awarded' => true,
                    'awarded_at' => now(),
                ]);
            }

            DB::commit();

            return back()->with('status', "Successfully recorded attendance and awarded {$points} points to {$user->name}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to award points: ' . $e->getMessage());
        }
    }
}
