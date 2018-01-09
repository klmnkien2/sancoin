<?php

namespace Modules\Pb\Http\Middleware;

use Closure;
use Modules\Pb\Services\WalletService;

class RefeshOrderStatus
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->walletService->refeshOrderStatus();
        
        return $next($request);
    }
}
