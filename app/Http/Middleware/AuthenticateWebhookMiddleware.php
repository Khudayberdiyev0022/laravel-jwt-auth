<?php

namespace App\Http\Middleware;

use Closure;
use Database\Factories\WebhookSignatureFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWebhookMiddleware
{
  public function handle(Request $request, Closure $next): Response
  {
    // check we were sent a signature
    abort_unless($request->hasHeader('X-Signature'), 401);
    // rebuild our signature locally
    $factory           = new WebhookSignatureFactory();
    $expectedSignature = $factory->generate($request->url(), $request->all());
    // if our signatures don't match, return a 401 error
    if (!hash_equals($request->header('X-Signature'), $expectedSignature)) {
      abort(401);
    }

    return $next($request);
  }
}
