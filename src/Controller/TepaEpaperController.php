<?php

namespace Q\Feeder\Controller;

use Qlurk\ApiClient as Qlurk;

class TepaEpaperController extends ControllerBase
{
    public function logic(array $params): array
    {

        $links = $this->epaperLinks();

        return [
            'lang' => 'zh-TW',
            'title' => "台灣電力企業聯合會各期電子報",
            'desc' => "台灣電力企業聯合會各期電子報",
            'link' => 'http://www.tepa108.org.tw/meetings/ePaper',
            'items' => array_map(function($link) {
                return [
                    'title' => $link['name'],
                    'url' => $link['url'],
                    'body' => $link['name'],
                    'guid' => $link['url'],
                    'time' => $link['time'],
                ];
            }, $links),
        ];
    }

    public function epaperLinks()
    {
        $body = $this->getRaw('http://www.tepa108.org.tw/meetings/ePaper');

        $result = [];
        foreach (explode("\n", $body) as $line) {
            preg_match('@/EpaperHtm/[^"]+.htm@', $line, $matches);
            if (count($matches) === 0) {
                continue;
            }
            $url = $matches[0];

            preg_match('@<a[^>]+>([^<]+)@', $line, $matches);
            $name = $matches[1];

            $time = substr($url, 11, 14);
            $time = sprintf(
                "%04d-%02d-%02d %02d:%02d:%02d",
                substr($time, 0, 4),
                substr($time, 4, 2),
                substr($time, 6, 2),
                substr($time, 8, 2),
                substr($time, 10, 2),
                substr($time, 12, 2),
            );
            $time = strtotime($time);

            $result[] = [
                'name' => $name,
                'url' => sprintf('http://www.tepa108.org.tw%s', $url),
                'time' => $time,
            ];
        }

        return $result;
    }
}
