<?php

namespace Q\Feeder\Controller;

class IndexController extends ControllerBase
{
    protected $template = 'index';

    public function logic(array $params): array
    {
        return [
            'content' => file_get_contents(__DIR__ . '/../../README.md'),
        ];
    }
}
