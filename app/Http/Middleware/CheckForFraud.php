<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AIModelService;
use Symfony\Component\HttpFoundation\Response;

class CheckForFraud
{
    protected $aiModelService;

    public function __construct(AIModelService $aiModelService)
    {
        $this->aiModelService = $aiModelService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request first
        $response = $next($request);

        // After the request is processed, check if a new bid was created
        // This is a simplified example - in a real application, you might want to use events instead
        if ($request->is('auctions/*/bid') && $request->isMethod('post')) {
            // Assuming the bid was just created and is available in the session
            if ($bid = session('new_bid')) {
                // Check for fraud asynchronously (you might want to use a queue for this)
                $this->aiModelService->checkForFraud($bid);
            }
        }

        return $response;
    }
}