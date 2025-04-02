<?php
namespace App\Middleware;

use App\Wrappers\Env;
use App\Wrappers\Session;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface {
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        if (!str_contains($path, '/login') && !Session::isLoggedIn()) {
            return new RedirectResponse(Env::app_url('/login'));
        }

        return $handler->handle($request);
    }
}
