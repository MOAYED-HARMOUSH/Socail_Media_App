<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShouldFollowPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $is_admin = $request->user()->pages()->find($request->page_id);
        $is_member = $request->user()->memberPages()->find($request->page_id);
        if ($is_admin == null && $is_member == null)
            return response()->json([
                'Message' => 'You Must be Member or Admin'
            ]);
        return $next($request);
    }
}
