<?php

namespace Odin\http\middleware;

use \Closure;
use Odin\http\server\Response;

interface IMiddleware
{
    public function handle($request, $response, Closure $next): Response;
}
