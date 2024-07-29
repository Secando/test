<?php

namespace App\Middleware;

use App\Exceptions\ValidationException;
use App\Session;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\Twig;


class FilterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Twig $twig,
        private readonly Session $session,
        private readonly ResponseFactoryInterface $response
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {


        $request->getQueryParams();
        $referer = $request->getHeader('referer');
        $currentParams =$request->getQueryParams();






        if($referer) {
            $refererQuery = explode('&',str_replace('?&','&',(str_replace('http://localhost:8000/catalog/?','',$referer[0]))));
            if(stripos($refererQuery[0],'http://')===0){
                return $handler->handle($request);
            }

            foreach ($refererQuery as $k=>$v) {
                $newK = explode('=',$v)[0];
                $newV = explode('=',$v)[1];
                $refererQuery[$newK] = $newV;
                unset($refererQuery[$k]);

            }
            if($refererQuery==$currentParams){
                return $handler->handle($request);
            }
            if(key_exists('rateSort',$currentParams) and key_exists('rateSort',$refererQuery)){
                if($refererQuery['rateSort'] == 'desc'){
                    $currentParams['rateSort']='asc';
                }
            }if(key_exists('priceSort',$currentParams) and key_exists('priceSort',$refererQuery)){
                if($refererQuery['priceSort'] == 'desc'){
                    $currentParams['priceSort']='asc';
                }
            }
            $oldParamas =$currentParams;
            foreach ($currentParams as $k=>$v){
                if(key_exists($k,$refererQuery)){
                    $refererQuery[$k] = $v;
                    unset($currentParams[$k]);
                }
            }
            $newQuery='';
            $resultQuery=array_merge($currentParams,$refererQuery);

            foreach ($resultQuery as $k=>$v){
                $newQuery .= $k.'='.$v.'&';

            }
            $newQuery = '?'.rtrim($newQuery,'&');
            if(!array_diff($refererQuery,$oldParamas)){
                return $handler->handle($request);
            }


            return $this->response->createResponse()->withStatus(302)->withHeader('Location', '/catalog/'.$newQuery);
        }

        return $handler->handle($request);
    }
}
