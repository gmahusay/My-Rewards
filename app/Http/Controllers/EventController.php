<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of active events.
     */
    public function index()
    {
        $user = auth()->user();
        
        // If user is business, they should probably see their own list in the business namespace
        // but for customers/employees, they see events from their assigned business
        if ($user->hasRole('business')) {
            return redirect()->route('business.events.index');
        }

        $events = Event::where('business_id', $user->business_id)
            ->where('is_active', true)
            ->where('event_date', '>', now())
            ->orderBy('event_date', 'asc')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    /**
     * Join an event.
     */
    public function join(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|in:going,maybe,not_going',
        ]);

        $user = auth()->user();
        $status = $request->status;

        if (!$event->is_active || $event->event_date->isPast()) {
            return back()->with('error', 'This event is no longer active.');
        }

        $user->joinedEvents()->syncWithoutDetaching([
            $event->id => ['status' => $status]
        ]);

        // Notify the business owner (only if they are going or maybe)
        if ($status !== 'not_going') {
            $business = \App\Models\User::find($event->business_id);
            if ($business) {
                $business->notify(new \App\Notifications\UserJoinedEvent($event, $user));
            }
        }

        $statusText = [
            'going' => "going to",
            'maybe' => "maybe attending",
            'not_going' => "not going to",
        ];

        return back()->with('status', "Your status has been updated to {$statusText[$status]} '{$event->title}'.");
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $user = auth()->user();

        // Ensure user is from the same business or is the business owner
        $isOwner = $user->hasRole('business') && $event->business_id === $user->id;
        $isMember = $event->business_id === $user->business_id;

        if (!$isOwner && !$isMember) {
            abort(403);
        }

        $event->load([
            'participants', 
            'comments' => function($query) {
                $query->whereNull('parent_id')->with(['user', 'replies.user', 'likes']);
            }, 
            'reactions'
        ]);

        $eligibleUsers = collect();
        if ($isOwner) {
            $eligibleUsers = $user->employees->concat($user->customers);
        }

        return view('events.show', compact('event', 'isOwner', 'eligibleUsers'));
    }

    /**
     * Post a comment on an event.
     */
    public function comment(Request $request, Event $event)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $event->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        $message = $request->parent_id ? 'Reply posted successfully!' : 'Comment posted successfully!';
        return back()->with('status', $message);
    }

    /**
     * Like/Unlike a comment.
     */
    public function commentLike(\App\Models\Comment $comment)
    {
        $user = auth()->user();
        
        if ($comment->likes()->where('user_id', $user->id)->exists()) {
            $comment->likes()->detach($user->id);
        } else {
            $comment->likes()->attach($user->id);
        }

        return back();
    }

    /**
     * React to an event.
     */
    public function react(Request $request, Event $event)
    {
        $request->validate([
            'type' => 'required|string|in:like,dislike,love,haha,wow,sad,angry',
        ]);

        $user = auth()->user();
        $type = $request->type;

        $existingReaction = $event->reactions()->where('user_id', $user->id)->first();

        if ($existingReaction) {
            if ($existingReaction->type === $type) {
                // Remove reaction if same type is clicked again
                $existingReaction->delete();
                return back();
            } else {
                // Change reaction type
                $existingReaction->update(['type' => $type]);
            }
        } else {
            // Create new reaction
            $event->reactions()->create([
                'user_id' => $user->id,
                'type' => $type,
            ]);
        }

        return back();
    }
}
