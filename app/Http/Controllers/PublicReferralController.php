<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\ReferralCategory;
use App\Models\ReferralVisit;
use App\Models\User;

class PublicReferralController extends Controller
{
    /**
     * Handle a public referral link: /r/{encCategory}/{encReferrer}
     */
    public function handle($encCategory, $encReferrer)
    {
        try {
            $categoryData = Crypt::decryptString($encCategory);
            $referrerData = Crypt::decryptString($encReferrer);
        } catch (\Exception $e) {
            abort(404);
        }

        // Expect categoryData to be the category slug
        $category = ReferralCategory::where('name', $categoryData)->orWhere('slug', $categoryData)->first();
        if (! $category) abort(404);

        // referrerData could be id or slug
        $referrer = User::where('id', $referrerData)->orWhere('slug', $referrerData)->first();
        if (! $referrer) abort(404);

        // Record the visit
        ReferralVisit::create([
            'category_id' => $category->id,
            'referrer_id' => $referrer->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Optionally set a cookie to remember the referral for signups
        $cookie = cookie('referral', json_encode([
            'category' => $category->id,
            'referrer' => $referrer->id,
        ]), 60 * 24 * 7); // 7 days

        // Redirect to the business referral landing page (if exists) or home
        $redirectUrl = route('referrals.landing', ['category' => $category->id]);
        return redirect($redirectUrl)->withCookie($cookie);
    }
}
