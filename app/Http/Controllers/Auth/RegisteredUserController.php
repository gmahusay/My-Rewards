<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Check for referral cookie and create referral record if present
        $referralCookie = $request->cookie('referral');
        if ($referralCookie) {
            try {
                $data = json_decode($referralCookie, true);
                if (!empty($data['category']) && !empty($data['referrer'])) {
                    $category = \App\Models\ReferralCategory::find($data['category']);
                    if ($category) {
                        // Associate the user with the business
                        $user->update([
                            'business_id' => $category->business_id,
                            'role' => 'customer',
                        ]);

                        \App\Models\Referral::create([
                            'category_id' => $data['category'],
                            'referrer_id' => $data['referrer'],
                            'referred_email' => $user->email,
                            'status' => 'pending',
                        ]);

                        // Automatically join the user to active campaigns of the business
                        $campaigns = \App\Models\Gamification\Campaign::where('business_id', $category->business_id)
                            ->where('is_active', true)
                            ->get();

                        foreach ($campaigns as $campaign) {
                            $participant = \App\Models\Gamification\CampaignParticipant::create([
                                'campaign_id'  => $campaign->id,
                                'user_id'      => $user->id,
                                'is_completed' => false,
                            ]);

                            foreach ($campaign->targets as $target) {
                                \App\Models\Gamification\CampaignProgress::create([
                                    'participant_id' => $participant->id,
                                    'target_id'      => $target->id,
                                    'current_value'  => 0,
                                    'is_completed'   => false,
                                ]);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // ignore if cookie or process fails
            }
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
