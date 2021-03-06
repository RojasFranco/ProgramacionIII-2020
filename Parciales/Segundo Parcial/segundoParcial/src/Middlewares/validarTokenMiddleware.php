<?php
namespace App\Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Utils\TokenJwt;
use Exception;

class ValidarTokenMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $headers = $request->getHeaders();
        
        if(!isset($headers["token"])){
            throw new \Slim\Exception\HttpBadRequestException($request, "Token no ingresado");
        }
        $token = $headers["token"][0];
        
        try{
            TokenJwt::MostrarDatos($token);            //Aca puedo validar tipo
            $response = $handler->handle($request); //OJO esta linea va al controlleR. Hacer validacion antes
            $existingContent = (string) $response->getBody();            
            $response = new Response();
            $response->getBody()->write($existingContent);
            return $response;
            //throw new \Slim\Exception\HttpUnauthorizedException($request); Para alguna condicion no cumplida
        }
        catch(Exception $err){
            throw $err;
        }
        
    }
}