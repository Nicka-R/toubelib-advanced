<?php
namespace gateway\middlewares\cors;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AddHeaders {
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface 
    {
        $response = $next->handle($request);
        return $response->withHeader('Content-Language', 'fr-FR')
        ->withHeader('Cache-Control', 'max-age='. 60*60*2);
    }
}