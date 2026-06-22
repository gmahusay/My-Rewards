<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClaimController extends Controller
{
    /**
     * Display a listing of available claim categories.
     */
    public function index()
    {
        $user = auth()->user();
        $business = User::find($user->business_id);
        
        if (!$business) {
            return redirect()->route('dashboard')->with('error', 'No business associated with your account.');
        }

        $categories = $business->claimCategories()->where('is_active', true)->get();

        return view('customer.claims.categories', compact('business', 'categories'));
    }

    /**
     * Display the employee's claim history.
     */
    public function history()
    {
        $claims = auth()->user()->claims()->with(['business', 'category'])->latest()->paginate(10);
        return view('customer.claims.index', compact('claims')); // Reusing the same view as customer
    }

    /**
     * Display the employee's claim history filtered by category.
     */
    public function categoryClaims(\App\Models\ClaimCategory $category)
    {
        $claims = auth()->user()->claims()
            ->where('category_id', $category->id)
            ->with(['business', 'category'])
            ->latest()
            ->paginate(10);
            
        return view('customer.claims.index', compact('claims', 'category'));
    }

    /**
     * Show the form for creating a new claim.
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $business = User::find($user->business_id);
        
        if (!$business) {
            return redirect()->route('dashboard')->with('error', 'No business associated with your account.');
        }

        if (!request()->has('category_id')) {
            return redirect()->route('employee.claims.index')->with('info', 'Please select a category first.');
        }

        $categories = $business->claimCategories()->where('is_active', true)->get();

        // Validate selected category
        if ($request->has('category_id')) {
            $selectedCategory = $categories->find($request->category_id);
            if ($selectedCategory && $selectedCategory->end_date && $selectedCategory->end_date->isPast()) {
                return redirect()->route('employee.claims.index')->with('error', 'This claim category has expired.');
            }
        }

        return view('customer.claims.create', compact('business', 'categories')); // Reusing the same view as customer
    }

    /**
     * Store a newly created claim.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'category_id' => ['required', 'exists:claim_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'invoice_number' => [
                'required', 
                'string', 
                'max:255', 
                \Illuminate\Validation\Rule::unique('claims')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                })
            ],
            'store_name' => ['required', 'string', 'max:255'],
            'document' => ['nullable', 'file', 'mimes:pdf,jpg,png,jpeg', 'max:5120'], // 5MB limit
        ]);

        $category = \App\Models\ClaimCategory::find($request->category_id);
        if ($category->end_date && $category->end_date->isPast()) {
            return redirect()->back()->with('error', 'This claim category has expired and cannot be selected.');
        }

        $data = [
            'user_id' => $user->id,
            'business_id' => $user->business_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'invoice_number' => $request->invoice_number,
            'store_name' => $request->store_name,
            'status' => 'pending',
        ];

        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('claims', 'public');
        }

        $claim = Claim::create($data);

        // Notify the business owner
        $business = \App\Models\User::find($user->business_id);
        if ($business) {
            $business->notify(new \App\Notifications\ClaimSubmitted($claim));
        }

        // Update Gamification Progress for Claim
        app(\App\Services\Gamification\GamificationService::class)->updateCampaignProgress(
            $user->id,
            'claim',
            1
        );

        return redirect()->route('employee.claims.index')->with('status', 'Claim submitted successfully!');
    }

    /**
     * Display the specified claim.
     */
    public function show(Claim $claim)
    {
        if ($claim->user_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.claims.show', compact('claim')); // Reusing customer view
    }
}
