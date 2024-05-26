<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subscription_plan_id', 'start_date', 'end_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            // Use the user's purchase date as the starting point
            $startDate = Carbon::parse($subscription->start_date);

            // Calculate the end date based on the associated plan's duration_quantity and duration_unit
            $endDate = $subscription->subscriptionPlan->calculateEndDate($startDate);

            // Set the calculated end date
            $subscription->end_date = $endDate;
        });
    }

    public function isActive()
    {
        return $this->end_date && now()->lte(Carbon::parse($this->end_date));
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
