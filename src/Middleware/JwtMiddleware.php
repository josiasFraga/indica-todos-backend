<?php
namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Cake\Http\Exception\UnauthorizedException;
use Firebase\JWT\Key;

class JwtMiddleware
{
    private $publicRoutes = [
        '/auth/login',
        '/users/add',
        '/services/by-provider',
        '/service-categories/index',
        '/service-subcategories/index',
        '/service-providers/index',
        '/service-providers/reviews',
        '/user-locations/add',
        '/user-locations/last',
        '/service-provider-visits/add',
        '/measurement-units/index',
        '/change-password/send-code',
        '/change-password/verify-code',
        '/change-password/change-password',
        '/webhooks/pagseguro',
        '/service-provider-photos/index',
        '/service-provider-photos/upload',
        '/service-provider-photos/delete',
    ];

    public function __invoke(ServerRequest $request, Response $response, $next)
    {
        $url = $request->getUri()->getPath();
        $path = parse_url($url, PHP_URL_PATH);
        $pathWithoutExtension = preg_replace('/\.json$/', '', $path);

        if (in_array($pathWithoutExtension, $this->publicRoutes)) {
            return $next($request, $response);
        }

        $header = $request->getHeaderLine('Authorization');
        $bearerToken = str_replace('Bearer ', '', $header);

        if (!$bearerToken) {
            throw new UnauthorizedException("Token não encontrado");
        }

        try {
            $payload = JWT::decode($bearerToken, new Key(Security::getSalt(), 'HS256'));
            
        } catch (\Exception $e) {
            throw new UnauthorizedException("Token inválido");
        }

        $request = $request->withAttribute('jwtPayload', $payload);

        return $next($request, $response);
    }
}
