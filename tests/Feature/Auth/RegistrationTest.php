<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_users_registering_with_referral_cookie_join_campaigns(): void
    {
        // 1. Create Business
        $business = \App\Models\User::factory()->create([
            'role' => 'business',
        ]);

        // 2. Create Referrer
        $referrer = \App\Models\User::factory()->create([
            'business_id' => $business->id,
            'role' => 'customer',
        ]);

        // 3. Create Referral Category (Campaign)
        $category = \App\Models\ReferralCategory::create([
            'business_id' => $business->id,
            'name' => 'Test Referral Campaign',
            'referral_link' => 'encrypted_token_value',
            'points_reward' => 50,
            'is_active' => true,
        ]);

        // 4. Create Active Gamification Campaign
        $campaign = \App\Models\Gamification\Campaign::create([
            'business_id' => $business->id,
            'title' => 'Referral Fest',
            'reward_points' => 100,
            'is_active' => true,
        ]);

        $target = $campaign->targets()->create([
            'level' => 1,
            'target_type' => 'referral',
            'target_value' => 5,
        ]);

        // 5. Send registration request with referral cookie
        $cookieValue = json_encode([
            'category' => $category->id,
            'referrer' => $referrer->id,
        ]);

        $response = $this->withUnencryptedCookie('referral', $cookieValue)
            ->post('/register', [
                'name' => 'Referred User',
                'email' => 'referred@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        // 6. Assertions
        $newUser = \App\Models\User::where('email', 'referred@example.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals($business->id, $newUser->business_id);
        $this->assertEquals('customer', $newUser->role);

        // Assert referral record exists
        $referral = \App\Models\Referral::where('referred_email', 'referred@example.com')->first();
        $this->assertNotNull($referral);
        $this->assertEquals($category->id, $referral->category_id);
        $this->assertEquals($referrer->id, $referral->referrer_id);
        $this->assertEquals('pending', $referral->status);

        // Assert user enrolled in gamification campaign
        $participant = \App\Models\Gamification\CampaignParticipant::where('campaign_id', $campaign->id)
            ->where('user_id', $newUser->id)
            ->first();
        $this->assertNotNull($participant);

        // Assert progress record created
        $progress = \App\Models\Gamification\CampaignProgress::where('participant_id', $participant->id)
            ->where('target_id', $target->id)
            ->first();
        $this->assertNotNull($progress);
        $this->assertEquals(0, $progress->current_value);
    }
}
