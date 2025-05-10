<?php
// app/Http/Controllers/AuctionController.php
namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuctionController extends Controller
{
    /**
     * Display a listing of auctions.
     */
    public function index(Request $request)
    {
        $query = Auction::with('seller');
        
        // Apply filters if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Default sort by newest
        $query->orderBy('created_at', 'desc');
        
        $auctions = $query->paginate(12);
        
        return view('auctions.index', compact('auctions'));
    }

    /**
     * Show the form for creating a new auction.
     */
    public function create()
    {
        return view('auctions.create');
    }

    /**
     * Store a newly created auction in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_price' => 'required|numeric|min:0.01',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
        ]);
        
        $auction = new Auction($validated);
        $auction->seller_id = Auth::id();
        $auction->current_price = $validated['starting_price'];
        $auction->status = 'active';
        $auction->save();
        
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction created successfully.');
    }

    /**
     * Display the specified auction.
     */
    public function show(Auction $auction)
    {
        $auction->load(['seller', 'bids' => function($query) {
            $query->where('is_fraud', false)
                  ->with('user')
                  ->orderBy('bid_amount', 'desc');
        }]);
        
        // Get bid history for this auction
        $bidHistory = $auction->bids()
            ->where('is_fraud', false)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Check if auction has ended
        $isEnded = Carbon::now()->isAfter($auction->end_time);
        
        // Get the highest bid
        $highestBid = $auction->highestBid();
        
        return view('auctions.show', compact('auction', 'bidHistory', 'isEnded', 'highestBid'));
    }

    /**
     * Show the form for editing the specified auction.
     */
    public function edit(Auction $auction)
    {
        // Only allow the seller or an admin to edit
        if (Auth::id() !== $auction->seller_id && !Auth::user()->role !== 'admin') {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to edit this auction.');
        }
        
        return view('auctions.edit', compact('auction'));
    }

    /**
     * Update the specified auction in storage.
     */
    public function update(Request $request, Auction $auction)
    {
        // Only allow the seller or an admin to update
        if (Auth::id() !== $auction->seller_id && !Auth::user()->role !== 'admin') {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to update this auction.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_price' => 'required|numeric|min:0.01',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:active,completed,cancelled',
        ]);
        
        // Only allow changing starting price if no bids have been placed
        if ($auction->bids()->count() > 0 && $auction->starting_price != $validated['starting_price']) {
            return redirect()->route('auctions.edit', $auction)
                ->with('error', 'Cannot change starting price after bids have been placed.')
                ->withInput();
        }
        
        $auction->update($validated);
        
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction updated successfully.');
    }

    /**
     * Remove the specified auction from storage.
     */
    public function destroy(Auction $auction)
    {
        // Only allow the seller or an admin to delete
        if (Auth::id() !== $auction->seller_id && !Auth::user()->role !== 'admin') {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to delete this auction.');
        }
        
        // Don't allow deletion if bids exist
        if ($auction->bids()->count() > 0) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'Cannot delete auction after bids have been placed. Mark it as cancelled instead.');
        }
        
        $auction->delete();
        
        return redirect()->route('auctions.index')
            ->with('success', 'Auction deleted successfully.');
    }

    /**
     * Mark an auction as completed.
     */
    public function complete(Auction $auction)
    {
        // Only allow the seller or an admin to complete
        if (Auth::id() !== $auction->seller_id && !Auth::user()->role !== 'admin') {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to complete this auction.');
        }
        
        $auction->status = 'completed';
        $auction->save();
        
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction marked as completed.');
    }

    /**
     * Mark an auction as cancelled.
     */
    public function cancel(Auction $auction)
    {
        // Only allow the seller or an admin to cancel
        if (Auth::id() !== $auction->seller_id && !Auth::user()->role !== 'admin') {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You do not have permission to cancel this auction.');
        }
        
        $auction->status = 'cancelled';
        $auction->save();
        
        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction cancelled successfully.');
    }

    /**
     * Show auctions where the current user is the seller.
     */
    public function myAuctions()
    {
        $auctions = Auction::where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('auctions.my-auctions', compact('auctions'));
    }
}