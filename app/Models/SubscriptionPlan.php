<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'features',
        'is_active'
    ];

    protected $casts = [
        'features' => 'json',
        'is_active' => 'boolean'
    ];

    public $incrementing = true;
    protected $keyType = 'int';

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
