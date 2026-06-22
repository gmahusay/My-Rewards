<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasGamification;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasGamification;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'business_id',
        'profile_photo_path',
        'points',
        'website_logo_path',
        'website_name',
        'company_name',
        'company_address',
        'company_contact_number',
        'company_contact_person',
        'payment_settings',
        'preferred_gateway',
    ];

    /**
     * Get transactions where user is the sender.
     */
    public function sentTransactions()
    {
        return $this->hasMany(PointTransaction::class, 'sender_id');
    }

    /**
     * Get transactions where user is the receiver.
     */
    public function receivedTransactions()
    {
        return $this->hasMany(PointTransaction::class, 'receiver_id');
    }

    /**
     * Add points to the user.
     * 
     * @param int $amount
     * @param string $description
     * @param User|null $sender
     * @return PointTransaction
     */
    public function addPoints(int $amount, string $description, ?User $sender = null): PointTransaction
    {
        $this->increment('points', $amount);

        return PointTransaction::create([
            'sender_id' => $sender ? $sender->id : null,
            'receiver_id' => $this->id,
            'amount' => $amount,
            'description' => $description,
        ]);
    }

    /**
     * Deduct points from the user.
     * 
     * @param int $amount
     * @throws \Exception
     */
    public function deductPoints(int $amount)
    {
        if ($this->points < $amount) {
            throw new \Exception("Insufficient points balance.");
        }

        $this->decrement('points', $amount);
    }

    /**
     * Get the business that owns the user.
     */
    public function business(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    /**
     * Get the employees for the business.
     */
    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'business_id')->where('role', 'employee');
    }

    /**
     * Get the customers for the business.
     */
    public function customers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'business_id')->where('role', 'customer');
    }

    /**
     * Get the products for the business.
     */
    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class, 'business_id');
    }

    /**
     * Get the orders placed by the user.
     */
    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Get the orders received by the business.
     */
    public function businessOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'business_id');
    }

    /**
     * Add an employee to the business.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function addEmployee(array $data): User
    {
        $data['role'] = 'employee';
        $data['business_id'] = $this->id;
        
        return User::create($data);
    }

    /**
     * Add a customer to the business.
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function addCustomer(array $data): User
    {
        $data['role'] = 'customer';
        $data['business_id'] = $this->id;
        
        return User::create($data);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Get the business associated with this user for branding purposes.
     *
     * @return User|null
     */
    public function getBrandingBusiness(): ?User
    {
        if ($this->hasRole('business')) {
            return $this;
        }

        return $this->business;
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the claims submitted by this user (customer).
     */
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Get the claims submitted to this user (business).
     */
    public function businessClaims()
    {
        return $this->hasMany(Claim::class, 'business_id');
    }
    /**
     * Get the claim categories created by this business.
     */
    public function claimCategories()
    {
        return $this->hasMany(ClaimCategory::class, 'business_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'payment_settings' => 'array',
        ];
    }

    /**
     * Get the donation categories created by this business.
     */
    public function nominationCategories()
    {
        return $this->hasMany(NominationCategory::class, 'business_id');
    }

    /**
     * Get the nominations sent by this user.
     */
    public function nominationsSent()
    {
        return $this->hasMany(Nomination::class, 'nominator_id');
    }

    /**
     * Get the nominations received by this user.
     */
    public function nominationsReceived()
    {
        return $this->hasMany(Nomination::class, 'nominee_id');
    }

    /**
     * Get the events created by this business.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'business_id');
    }

    /**
     * Get the events this user is participating in.
     */
    public function joinedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_participants')
            ->withPivot('status', 'attended_at', 'points_awarded', 'awarded_at')
            ->withTimestamps()
            ->using(\Illuminate\Database\Eloquent\Relations\Pivot::class)
            ->as('pivot')
            ->withCasts([
                'awarded_at' => 'datetime',
                'attended_at' => 'datetime',
            ]);
    }

    /**
     * Get the referral categories created by this business.
     */
    public function referralCategories()
    {
        return $this->hasMany(ReferralCategory::class, 'business_id');
    }

    /**
     * Get the referrals made by this user.
     */
    public function madeReferrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Get the referral campaigns this user has joined.
     */
    public function joinedReferralCategories()
    {
        return $this->belongsToMany(ReferralCategory::class, 'referral_category_participants', 'user_id', 'category_id')
            ->withTimestamps();
    }

    /**
     * Get the KPI categories created by this business.
     */
    public function kpiCategories()
    {
        return $this->hasMany(KpiCategory::class, 'business_id');
    }

    /**
     * Get the KPIs submitted by this user.
     */
    public function kpis()
    {
        return $this->hasMany(Kpi::class, 'user_id');
    }

    /**
     * Return an encrypted identifier for referral links (prefer slug if available)
     */
    public function getReferralIdentifierAttribute(): string
    {
        $value = $this->slug ?? $this->id;
        return \Illuminate\Support\Facades\Crypt::encryptString((string) $value);
    }
}
