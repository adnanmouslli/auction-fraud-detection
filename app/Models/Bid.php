<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'auction_id',
        'bid_amount',
        'is_fraud',
        'fraud_score',
    ];

    protected $casts = [
        'is_fraud' => 'boolean',
        'fraud_score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function fraudAlert()
    {
        return $this->hasOne(FraudAlert::class);
    }

    public function isFraudulent()
    {
        return $this->is_fraud;
    }
}
