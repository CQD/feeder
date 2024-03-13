<?php

function e($text, $type = 'html')
{
    $funcs = [
        'html' => 'htmlspecialchars',
        'js' => 'json_encode',
        'raw' => 'strval',
    ];

    return $funcs[$type]($text);
}
