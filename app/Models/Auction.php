<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'starting_price',
        'current_price',
        'start_time',
        'end_time',
        'seller_id',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function highestBid()
    {
        return $this->bids()->where('is_fraud', 0)->orderBy('bid_amount', 'desc')->first();
    }

    public function isActive()
    {
        return $this->status === 'active' && now()->between($this->start_time, $this->end_time);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now());
    }
}
