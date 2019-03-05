<?php

namespace Odin\http\middleware;

class Middleware
{

    public static function call($middleware, $next, $request, $response)
    {
        return call_user_func_array([$middleware, 'handle'], [$request, $response, $next]);
    }

    public static function executeMiddlewares($middlewares, &$container){
        foreach ($middlewares as $middleware)
        {
            if (is_string($middleware) || is_object($middleware)) {
                if (is_string($middlewares)) {
                    $middleware = new $middleware();
                }
                try
                {

                    if ($container->response instanceof \Odin\http\server\Response) {
                        $container->response = self::call($middleware, function($request, $response){
                            return $response;
                        }, $container->request, $container->response);
                    } else {
                        \Odin\utils\Errors::throwError('Retorno Inválido!', 'Todo middleware deve retornar \Odin\http\server\Response', 'bug');
                        die();
                        break;
                    }
                }
                catch (\Exception $e)
                {
                    echo 'Erro: '.$e->getMessage();
                    die;
                }
            }
        }
    }
}
