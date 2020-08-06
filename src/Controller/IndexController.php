<?php

namespace Q\Feeder\Controller;

class IndexController extends ControllerBase
{
    public function run($params)
    {
        header('Content-Type: text/plain');
        echo file_get_contents(__DIR__ . '/../../README.md');
    }

    public function logic(array $params): array
    {
        return [];
    }
}
