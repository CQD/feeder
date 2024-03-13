<?php

namespace Q\Feeder;

class Router
{
    public static function route($path){
        $routes = static::getRouteConfig();
        $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $path => $controller) {
                $r->addRoute('GET', $path, $controller);
            }
        });

        $routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $path);
        switch ($routeInfo[0]) {
        case \FastRoute\Dispatcher::FOUND:
            $controller = "\\Q\\Feeder\\Controller\\{$routeInfo[1]}";
            return [$controller, $routeInfo[2]];
        default:
            return [null, null];
        }
    }

    public static function getRouteConfig() : array
    {
        return [
            '/github/repo/{user}/{repo}/issuecomment' => 'IssueCommentController',
            '/vocus/user/{user}'       => 'VocusUserController',
            '/vocus/publication/{id}'  => 'VocusPublicationController',
            '/plurk/search/{keyword}'  => 'PlurkSearchController',
            '/tepa/epaper'  => 'TepaEpaperController',
            '/ptt/{board}/title/{regex}'  => 'PttTitleController',
            '/591/comu/{id}'  => 'FiveNineOneSaleController',
            '/' => 'IndexController',
        ];
    }
}
