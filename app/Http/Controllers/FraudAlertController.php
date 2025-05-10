<?php
// app/Http/Controllers/FraudAlertController.php
namespace App\Http\Controllers;

use App\Models\FraudAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FraudAlertController extends Controller
{
    /**
     * Display a listing of fraud alerts.
     */
    public function index(Request $request)
    {
        // Only allow admin access
        if (!Auth::user()->role === 'admin') {
            return redirect()->route('auctions.index')
                ->with('error', 'You do not have permission to view fraud alerts.');
        }
        
        $query = FraudAlert::with(['bid.user', 'bid.auction', 'resolver']);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $alerts = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('fraud-alerts.index', compact('alerts'));
    }

    /**
     * Display the specified fraud alert.
     */
    public function show(FraudAlert $fraudAlert)
    {
        // Only allow admin access
        if (!Auth::user()->role === 'admin') {
            return redirect()->route('auctions.index')
                ->with('error', 'You do not have permission to view fraud alerts.');
        }
        
        $fraudAlert->load(['bid.user', 'bid.auction', 'resolver']);
        
        return view('fraud-alerts.show', compact('fraudAlert'));
    }

    /**
     * Mark a fraud alert as resolved.
     */
    public function resolve(Request $request, FraudAlert $fraudAlert)
    {
        // Only allow admin access
        if (!Auth::user()->role === 'admin') {
            return redirect()->route('auctions.index')
                ->with('error', 'You do not have permission to resolve fraud alerts.');
        }
        
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);
        
        $fraudAlert->update([
            'status' => 'resolved',
            'resolved_by' => Auth::user()->getAuthIdentifier(),
        ]);
        
        // Confirm the bid is marked as fraudulent
        $fraudAlert->bid->update([
            'is_fraud' => true,
        ]);
        
        // If this was the highest bid, update the auction's current price
        $auction = $fraudAlert->bid->auction;
        if ($auction->current_price == $fraudAlert->bid->bid_amount) {
            // Find the next highest legitimate bid
            $nextHighestBid = $auction->bids()
                ->where('is_fraud', false)
                ->where('id', '!=', $fraudAlert->bid->id)
                ->orderBy('bid_amount', 'desc')
                ->first();
            
            if ($nextHighestBid) {
                $auction->current_price = $nextHighestBid->bid_amount;
            } else {
                // If no other bids, reset to starting price
                $auction->current_price = $auction->starting_price;
            }
            
            $auction->save();
        }
        
        return redirect()->route('fraud-alerts.index')
            ->with('success', 'Fraud alert marked as resolved.');
    }

    /**
     * Dismiss a fraud alert.
     */
    public function dismiss(Request $request, FraudAlert $fraudAlert)
    {
        // Only allow admin access
        if (!Auth::user()->role === 'admin') {
            return redirect()->route('auctions.index')
                ->with('error', 'You do not have permission to dismiss fraud alerts.');
        }
        
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);
        
        $fraudAlert->update([
            'status' => 'dismissed',
            'resolved_by' => Auth::user()->getAuthIdentifier(),
        ]);
        
        // Mark the bid as legitimate
        $fraudAlert->bid->update([
            'is_fraud' => false,
        ]);
        
        return redirect()->route('fraud-alerts.index')
            ->with('success', 'Fraud alert dismissed.');
    }

    /**
     * Get count of pending fraud alerts (for notifications).
     */
    public function pendingCount()
    {
        // Only allow admin access
        if (!Auth::user()->role !== 'admin') {
            return response()->json(['count' => 0]);
        }
        
        $count = FraudAlert::where('status', 'pending')->count();
        
        return response()->json(['count' => $count]);
    }
}