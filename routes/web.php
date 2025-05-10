<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\FraudAlertController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('auctions.index');
});

// Authentication routes (if using Laravel Breeze)
require __DIR__.'/auth.php';

// Public routes
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    // Auction routes
    Route::get('/my-auctions', [AuctionController::class, 'myAuctions'])->name('auctions.my');
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/auctions', [AuctionController::class, 'store'])->name('auctions.store');
    Route::get('/auctions/{auction}/edit', [AuctionController::class, 'edit'])->name('auctions.edit');
    Route::put('/auctions/{auction}', [AuctionController::class, 'update'])->name('auctions.update');
    Route::delete('/auctions/{auction}', [AuctionController::class, 'destroy'])->name('auctions.destroy');
    Route::put('/auctions/{auction}/complete', [AuctionController::class, 'complete'])->name('auctions.complete');
    Route::put('/auctions/{auction}/cancel', [AuctionController::class, 'cancel'])->name('auctions.cancel');
    
    // Bid routes
    Route::post('/auctions/{auction}/bid', [BidController::class, 'store'])->name('bids.store');
    Route::get('/my-bids', [BidController::class, 'myBids'])->name('bids.my');
});

Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');


// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/auctions', [DashboardController::class, 'auctions'])->name('dashboard.auctions');
    Route::get('/dashboard/fraud-alerts', [DashboardController::class, 'fraudAlerts'])->name('dashboard.fraud-alerts');
    
    // Bids management
    Route::get('/bids', [BidController::class, 'index'])->name('bids.index');
    Route::get('/bids/{bid}', [BidController::class, 'show'])->name('bids.show');
    Route::put('/bids/{bid}/mark-fraud', [BidController::class, 'markAsFraud'])->name('bids.mark-fraud');
    Route::put('/bids/{bid}/mark-legitimate', [BidController::class, 'markAsLegitimate'])->name('bids.mark-legitimate');
    
    // Fraud alerts management
    Route::get('/fraud-alerts', [FraudAlertController::class, 'index'])->name('fraud-alerts.index');
    Route::get('/fraud-alerts/{fraudAlert}', [FraudAlertController::class, 'show'])->name('fraud-alerts.show');
    Route::put('/fraud-alerts/{fraudAlert}/resolve', [FraudAlertController::class, 'resolve'])->name('fraud-alerts.resolve');
    Route::put('/fraud-alerts/{fraudAlert}/dismiss', [FraudAlertController::class, 'dismiss'])->name('fraud-alerts.dismiss');
    Route::get('/fraud-alerts/pending-count', [FraudAlertController::class, 'pendingCount'])->name('fraud-alerts.pending-count');
});