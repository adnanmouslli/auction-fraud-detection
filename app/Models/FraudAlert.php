<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_id',
        'reason',
        'status',
        'resolved_by',
    ];

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function isDismissed()
    {
        return $this->status === 'dismissed';
    }
}