<?php
// app/Services/AIModelService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Bid;
use App\Models\FraudAlert;

class AIModelService
{
    protected $apiUrl;

    public function __construct()
    {
        // Get API URL from .env or use default
        $this->apiUrl = env('AI_MODEL_API_URL', 'http://localhost:5000/predict');
    }

    /**
     * Check if a bid is fraudulent using the AI model
     * 
     * @param Bid $bid
     * @return array|null Result containing fraud status and score
     */
    public function checkForFraud(Bid $bid)
    {
        try {
            $response = Http::timeout(5)->post($this->apiUrl, [
                'user_id' => $bid->user_id,
                'auction_id' => $bid->auction_id,
                'bid_amount' => $bid->bid_amount
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                // Update the bid with the fraud detection results
                $bid->is_fraud = $result['fraud'] ? 1 : 0;
                $bid->fraud_score = $result['fraud_score'] ?? 0;
                $bid->save();

                // If the bid is flagged as fraudulent, create an alert
                if ($result['fraud']) {
                    $this->createFraudAlert($bid, $result);
                }
                return $result;
            }
            
        
        } catch (\Exception $e) {
            Log::error('Error connecting to fraud detection API', [
                'message' => $e->getMessage(),
                'bid_id' => $bid->id
            ]);
            
            return null;
        }
    }

    /**
     * Create a fraud alert for a suspicious bid
     * 
     * @param Bid $bid
     * @param array $result The result from the AI model
     * @return FraudAlert
     */
    protected function createFraudAlert(Bid $bid, array $result)
    {
        $reason = 'Suspicious activity detected';
        
        // Add more context to the reason based on the fraud score
        if (isset($result['fraud_score'])) {
            $score = $result['fraud_score'] * 100;
            $reason .= sprintf(' (Confidence: %.1f%%)', $score);
            
            if ($score > 90) {
                $reason .= ' - High risk';
            } elseif ($score > 70) {
                $reason .= ' - Medium risk';
            } else {
                $reason .= ' - Low risk';
            }
        }
        
        return FraudAlert::create([
            'bid_id' => $bid->id,
            'reason' => $reason,
            'status' => 'pending'
        ]);
    }

    /**
     * Batch process multiple bids for fraud detection
     * 
     * @param array $bids Array of Bid models
     * @return array Results for each bid
     */
    public function batchCheckForFraud(array $bids)
    {
        $results = [];
        
        foreach ($bids as $bid) {
            $results[$bid->id] = $this->checkForFraud($bid);
        }
        
        return $results;
    }
}