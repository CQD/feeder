<?php

include __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Asia/Taipei');

list($controller, $params) = route($argv[1] ?? $_SERVER['REQUEST_URI']);

if (!$controller) {
    http_response_code(404);
} else {
    (new $controller)->run($params);
}


//////////////////////////////////////////

function route($path){
    $routes = getRouteConfig();
    $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) use ($routes) {
        foreach ($routes as $path => $controller) {
            $r->addRoute('GET', $path, $controller);
        }
    });

    $routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $path);
    switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::FOUND:
        $controller = "\\Q\\Feeder\\Controller\\{$routeInfo[1]}";
        return [$controller, $routeInfo[2]];
    default:
        return [null, null];
    }
}

function getRouteConfig()
{
    return [
        '/github/repo/{user}/{repo}/issuecomment' => 'IssueCommentController',
        '/vocus/user/{user}'       => 'VocusUserController',
        '/vocus/publication/{id}'  => 'VocusPublicationController',
        '/' => 'IndexController',
    ];
}

function e($text, $type = 'html')
{
    $funcs = [
        'html' => 'htmlspecialchars',
        'js' => 'json_encode',
        'raw' => 'strval',
    ];

    return $funcs[$type]($text);
}
