<?php

namespace Q\Feeder\Controller;

class NdaiController extends ControllerBase
{
    public function logic(array $params): array
    {
        $rows = $this->get('https://www.newsdigest.ai/api/news');

        usort($rows, function($a, $b) {
            return strtotime($b['pub_date_time']) <=> strtotime($a['pub_date_time']);
        });

        $sectors = [
            'economics' => '經濟',
            'geopolitics' => '地緣政治',
            'technology' => '科技',
        ];

        return [
            'lang' => 'zh-TW',
            'title' => "News Digest AI（中文）",
            'desc' => "News Digest AI（中文）",
            'link' => "https://www.newsdigest.ai",
            'items' => array_map(function($row) use ($sectors) {
                return [
                    'title' => sprintf('[%s] %s', $sectors[$row['sector']] ?? $row['sector'], $row['title_CHT']),
                    'url' => $row['url'],
                    'body' => $row['summary_CHT'] . '<hr>' . sprintf('%s: <a href="%s">%s</a>', $row['source'], $row['url'], $row['title']),
                    'guid' => $row['url'],
                    'time' => strtotime($row['pub_date_time']),
                ];
            }, $rows),
        ];
    }
}
