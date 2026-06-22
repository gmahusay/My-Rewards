<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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

        if (!$user->hasRole('business')) {
            return back()->with('error', 'Points can only be added to business users.');
        }

        DB::transaction(function () use ($request, $user) {
            $user->addPoints($request->amount, "Admin adjusted points: +{$request->amount}");
        });

        return back()->with('status', 'Points added successfully.');
    }
}
