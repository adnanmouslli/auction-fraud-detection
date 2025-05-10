<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\FraudAlert;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
      
        // Only allow admin access
        if (!Auth::user()->role === 'admin') {
            return redirect()->route('auctions.index')
                ->with('error', 'You do not have permission to access the dashboard.');
        }

        // Get counts for dashboard stats
        $stats = [
            'total_users' => User::count(),
            'total_auctions' => Auction::count(),
            'total_bids' => Bid::count(),
            'total_fraud_alerts' => FraudAlert::count(),
            'pending_alerts' => FraudAlert::where('status', 'pending')->count(),
            'active_auctions' => Auction::active()->count(),
        ];

        // Fraud percentage
        $stats['fraud_percentage'] = Bid::count() > 0 
            ? round((Bid::where('is_fraud', true)->count() / Bid::count()) * 100, 2) 
            : 0;

        // Recent fraud alerts
        $recentAlerts = FraudAlert::with(['bid.user', 'bid.auction'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Fraud trend data (last 30 days)
        $fraudTrend = Bid::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_bids'),
                DB::raw('SUM(CASE WHEN is_fraud = 1 THEN 1 ELSE 0 END) as fraud_bids')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->fraud_rate = $item->total_bids > 0 
                    ? round(($item->fraud_bids / $item->total_bids) * 100, 2) 
                    : 0;
                return $item;
            });

        return view('dashboard.index', compact('stats', 'recentAlerts', 'fraudTrend'));
    }

    public function auctions()
    {
        $auctions = Auction::withCount(['bids', 'bids as fraud_bids_count' => function ($query) {
                $query->where('is_fraud', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.auctions', compact('auctions'));
    }

    public function fraudAlerts(Request $request)
    {
        $query = FraudAlert::with(['bid.user', 'bid.auction', 'resolver']);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $alerts = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('dashboard.fraud-alerts', compact('alerts'));
    }
}