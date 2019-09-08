<?php namespace iWedmak\Helper\Middleware;

use Closure;
use BadMethodCallException;

class FormatResponce
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        try
        {
            if (method_exists($response, 'getOriginalContent')) 
            {
                $data = $response->getOriginalContent();
                if(gettype($data)!='object' && isset($data['data']) && !empty($data['data']))
                {
                    if ($request->wantsJson() || !isset($data['view'])) 
                    {
                        $response=response()->json($data['data'])->setJsonOptions(JSON_NUMERIC_CHECK );
                        $response->header('Content-Length',mb_strlen($response->getContent()));
                    } 
                    else 
                    {
                        $response=response()->view($data['view'], $data['data']);
                    }
                }
            }
            
        }
        catch(BadMethodCallException $e)
        {
            
        }
        return $response;
    }
}
