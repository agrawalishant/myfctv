<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class subscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'duration_quantity', 'duration_unit', 'status', 'ads_removal'];

    public function calculateEndDate($startDate)
    {
        $startDate = Carbon::parse($startDate);
        $duration = $this->duration_quantity;

        switch ($this->duration_unit) {
            case 'day':
                return $startDate->addDays($duration);
            case 'month':
                return $startDate->addMonths($duration);
            case 'year':
                return $startDate->addYears($duration);
            default:
                throw new \InvalidArgumentException("Invalid duration unit: {$this->duration_unit}");
        }
    }
}
