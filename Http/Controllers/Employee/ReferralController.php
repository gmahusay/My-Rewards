// In the show() method, add:
public function show($category)
{
    $category = ReferralCategory::findOrFail($category);
    $user = auth()->user();
    
    // Check if user has already joined this campaign
    $hasJoined = Referral::where('user_id', $user->id)
        ->where('referral_category_id', $category->id)
        ->exists();
    
    return view('employee.referrals.show', compact('category', 'hasJoined'));
}

// Add the join() method:
public function join($category)
{
    $category = ReferralCategory::findOrFail($category);
    $user = auth()->user();
    
    // Check if already joined
    $exists = Referral::where('user_id', $user->id)
        ->where('referral_category_id', $category->id)
        ->exists();
    
    if (!$exists) {
        Referral::create([
            'user_id' => $user->id,
            'referral_category_id' => $category->id,
            'business_id' => $user->business_id,
        ]);
    }
    
    return redirect()->route('employee.referrals.show', $category->id)
        ->with('success', 'You have joined the referral campaign!');
}
