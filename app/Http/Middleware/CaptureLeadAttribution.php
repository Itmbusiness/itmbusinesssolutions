<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureLeadAttribution
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && $request->hasAny(['utm_source', 'utm_medium', 'utm_campaign'])) {
            $attribution = [];

            foreach (['utm_source', 'utm_medium', 'utm_campaign'] as $key) {
                $value = $request->query($key);

                $attribution[$key] = is_string($value)
                    ? mb_substr(trim(preg_replace('/[\x00-\x1F\x7F]/u', '', $value) ?? ''), 0, 150)
                    : '';
            }

            $request->session()->put('lead_attribution', $attribution);
        }

        return $next($request);
    }
}
