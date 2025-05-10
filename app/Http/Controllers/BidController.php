<?php
// app/Http/Controllers/BidController.php
namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Auction;
use App\Services\AIModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BidController extends Controller
{
    protected $aiModelService;
    
    public function __construct(AIModelService $aiModelService)
    {
        $this->aiModelService = $aiModelService;
    }
    
    /**
     * Store a newly created bid in storage.
     */
    public function store(Request $request, Auction $auction)
    {
        // Check if auction is active
        if (!$auction->isActive()) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Cannot place bid on an inactive auction.');
        }
        
        // Check if auction has ended
        if (Carbon::now()->isAfter($auction->end_time)) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Cannot place bid on an ended auction.');
        }
        
        // Validate the bid amount
        $validated = $request->validate([
            'bid_amount' => [
                'required',
                'numeric',
                'min:' . ($auction->current_price + 0.01)
            ],
        ], [
            'bid_amount.min' => 'Your bid must be higher than the current price of ' . number_format($auction->current_price, 2),
        ]);
        
        // Create the bid
        $bid = new Bid([
            'user_id' => Auth::id(),
            'auction_id' => $auction->id,
            'bid_amount' => $validated['bid_amount'],
        ]);
        $bid->save();
        
        // Update the auction's current price
        $auction->current_price = $validated['bid_amount'];
        $auction->save();
        
        // Check for fraud (asynchronously if possible)
        // You might want to implement queue jobs for this in production
        $fraudCheckResult = $this->aiModelService->checkForFraud($bid);
        
        // dd($fraudCheckResult);

        if ($fraudCheckResult && $fraudCheckResult['fraud']) {
            // For now, we'll just return a message to the user
            // In a real system, you might want to handle this differently
            return redirect()->route('auctions.show', $auction)
                ->with('warning', 'Your bid has been flagged for review by our fraud detection system. It may be rejected upon review.');
        }
        
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Bid placed successfully!');
    }
    
    /**
     * Show the bids placed by the current user.
     */
    public function myBids()
    {
        $bids = Bid::where('user_id', Auth::id())
            ->with('auction')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('bids.my-bids', compact('bids'));
    }
    
    /**
     * Show all bids (admin only).
     */
    public function index(Request $request)
    {
        // Only allow admin access
        if (!Auth::user()->role === 'admin') {
            return redirect()->route('auctions.index')
                ->with('error', 'You do not have permission to view all bids.');
        }
        
        $query = Bid::with(['user', 'auction']);
        
        // Apply filters
        if ($request->has('fraud')) {
            $query->where('is_fraud', $request->fraud == 'true');
        }
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('auction_id')) {
            $query->where('auction_id', $request->auction_id);
        }
        
        $bids = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('bids.index', compact('bids'));
    }
    
    /**
     * Show a specific bid (admin only).
     */
    public function show(Bid $bid)
    {
        // Only allow admin access or the user who placed the bid
        if (!Auth::user()->role !== 'admin' && Auth::user()->getAuthIdentifier() !== $bid->user_id) {
            return redirect()->route('auctions.index')
                ->with('error', 'You do not have permission to view this bid.');
        }
        
        $bid->load(['user', 'auction', 'fraudAlert']);
        
        return view('bids.show', compact('bid'));
    }
    
    /**
     * Mark a bid as fraudulent (admin only).
     */
    public function markAsFraud(Bid $bid)
    {
        // Only allow admin access
        if (!Auth::user()->role === 'admin') {
            return redirect()->route('auctions.index')
                ->with('error', 'You do not have permission to mark bids as fraudulent.');
        }
        
        $bid->is_fraud = true;
        $bid->save();
        
        // Create a fraud alert if one doesn't exist
        if (!$bid->fraudAlert) {
            $bid->fraudAlert()->create([
                'reason' => 'Manually flagged as fraudulent by admin',
                'status' => 'pending',
            ]);
        }
        
        return redirect()->back()
            ->with('success', 'Bid marked as fraudulent.');
    }
    
    /**
     * Mark a bid as legitimate (admin only).
     */
    public function markAsLegitimate(Bid $bid)
    {
        // Only allow admin access
        if (!Auth::user()->role === 'admin') {
            return redirect()->route('auctions.index')
                ->with('error', 'You do not have permission to mark bids as legitimate.');
        }
        
        $bid->is_fraud = false;
        $bid->save();
        
        // Update any existing fraud alert
        if ($bid->fraudAlert) {
            $bid->fraudAlert->update([
                'status' => 'dismissed',
                'resolved_by' => Auth::user()->getAuthIdentifier(),
            ]);
        }
        
        return redirect()->back()
            ->with('success', 'Bid marked as legitimate.');
    }
}