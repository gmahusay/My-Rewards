<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $business = Auth::user();

        // Ensure the logged-in user is a business
        if (!$business->hasRole('business')) {
             return back()->with('error', 'Unauthorized action.');
        }
        
        // Ensure the recipient belongs to this business
        if ($user->business_id !== $business->id) {
            return back()->with('error', 'You can only allocate points to your own employees or customers.');
        }

        try {
            DB::transaction(function () use ($request, $user, $business) {
                // Deduct from Business
                $business->deductPoints($request->amount);

                // Add to Recipient
                $user->addPoints($request->amount, "Received from Business: {$business->name}", $business);
            });

            return back()->with('status', 'Points allocated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
