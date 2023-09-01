<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;


class Logger
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $log = [
            'name' => $this->getFormatNameMethod($request->route()->uri),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'user_agent' => $request->header('User-Agent'),
            'request_ip' => $request->ip(),
            'response_content' => $response->getContent(),
        ];


        $log = new Log($log);
        $log->save();

        return $response;
    }

    private function getFormatNameMethod($name)
    {
        $name = explode('/', $name);
        $name = end($name);
        return $name;
    }
}