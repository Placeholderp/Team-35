<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProductIdCleanerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if this is a product route that has an ID parameter
        $path = $request->path();
        if (preg_match('/api\/products\/([^\/]+)/', $path, $matches)) {
            $productId = $matches[1];
            
            // Clean the ID by removing anything after a colon
            if (strpos($productId, ':') !== false) {
                $cleanId = explode(':', $productId)[0];
                
                // Create a new path with the cleaned ID
                $newPath = str_replace($productId, $cleanId, $path);
                
                // Update the request
                $request->server->set('REQUEST_URI', '/' . $newPath);
                
                // If you need to update the route parameters as well
                if ($request->route()) {
                    $parameters = $request->route()->parameters();
                    if (isset($parameters['product'])) {
                        $parameters['product'] = $cleanId;
                        $request->route()->setParameters($parameters);
                    }
                }
            }
        }

        return $next($request);
    }
}