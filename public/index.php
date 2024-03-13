<?php

use Q\Feeder\Router;

include __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Asia/Taipei');

list($controller, $params) = Router::route($argv[1] ?? $_SERVER['REQUEST_URI']);

foreach ($params as $k => $v) {
    $params[$k] = urldecode($v);
}

if (!$controller) {
    http_response_code(404);
} else {
    (new $controller)->run($params);
}


//////////////////////////////////////////

function e($text, $type = 'html')
{
    $funcs = [
        'html' => 'htmlspecialchars',
        'js' => 'json_encode',
        'raw' => 'strval',
    ];

    return $funcs[$type]($text);
}
