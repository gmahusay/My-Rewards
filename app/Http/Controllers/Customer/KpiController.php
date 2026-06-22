<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Kpi;
use App\Models\KpiCategory;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $businessId = $user->business_id;
        
        $categories = KpiCategory::where('business_id', $businessId)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->get();
            
        return view('customer.kpis.index', compact('categories'));
    }

    public function show(KpiCategory $category)
    {
        if ($category->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $myKpis = auth()->user()->kpis()
            ->where('category_id', $category->id)
            ->latest()
            ->get();

        return view('customer.kpis.show', compact('category', 'myKpis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:kpi_categories,id',
            'description' => 'required|string',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'description' => $request->description,
            'status' => 'pending',
        ];

        if ($request->hasFile('proof_image')) {
            $data['proof_image_path'] = $request->file('proof_image')->store('kpi-proofs', 'public');
        }

        $kpi = Kpi::create($data);

        // Notify Business Owner
        $business = $kpi->category->business;
        $business->notify(new \App\Notifications\NewKpiSubmitted($kpi));

        return back()->with('success', 'KPI submitted successfully!');
    }
}
