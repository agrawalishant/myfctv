<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens; // Add this line
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable; // Add HasApiTokens here

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'gender',
        'date_of_birth',
        'password',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Add other relationships, attributes, or methods as needed

    public function watchlist()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function payments()
    {
        return $this->hasMany(UserPayment::class);
    }

    public function hasActiveSubscription()
    {
        $subscription = $this->latestSubscription;

        if ($subscription && now()->between($subscription->start_date, $subscription->end_date)) {
            return true;
        }

        return false;
    }

    /**
     * Get the latest subscription for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestSubscription()
    {
        return $this->hasOne(UserSubscription::class)->latestOfMany();
    }

    /**
     * Define the direct relationship with the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscription()
    {
        return $this->hasOne(UserSubscription::class);
    }

    public function remainingDaysInSubscription()
    {
        $subscription = $this->latestSubscription;

        if ($subscription && now()->between($subscription->start_date, $subscription->end_date)) {
            return now()->diffInDays($subscription->end_date);
        }

        return 0;
    }
}