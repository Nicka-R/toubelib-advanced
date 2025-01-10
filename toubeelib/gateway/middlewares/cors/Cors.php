<?php
namespace gateway\middlewares\cors;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

class Cors
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
                             ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                             ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        return $response;
    }

    public function corsHeaders(Request $rq, RequestHandler $next): Response
    {
        if (! $rq->hasHeader('Origin'))
            throw new HttpUnauthorizedException($rq, "missing Origin Header (cors)");
        $response = $next->handle($rq);
        $response = $response
                ->withHeader('Access-Control-Allow-Origin',  $rq->getHeaderLine('Origin'))
                ->withHeader('Access-Control-Allow-Methods', 'POST, PUT, GET' )
                ->withHeader('Access-Control-Allow-Headers','Authorization' )
                ->withHeader('Access-Control-Max-Age', 3600)
                ->withHeader('Access-Control-Allow-Credentials', 'true');
        return $response;
    }
}