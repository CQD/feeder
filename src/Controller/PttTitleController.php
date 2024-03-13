<?php

namespace Q\Feeder\Controller;

class PttTitleController extends ControllerBase
{
    public function logic(array $params): array
    {
        ["board" => $board, "regex" => $pattern] = $params;

        $feed = $this->getXML(sprintf('https://www.ptt.cc/atom/%s.xml', urlencode($board)));
        $items = [];
        foreach ($feed->entry ?? [] as $entry) {
            $title = $entry->title;

            if (@!preg_match("@{$pattern}@i", $title)) {
                continue;
            }

            $items[] = [
                'title' => $title,
                'url' => $entry->link->attributes()["href"],
                'body' => nl2br($entry->content),
                'guid' => $entry->id,
                'time' => strtotime($entry->published),
            ];
        }

        return [
            'lang' => 'zh-TW',
            'title' => "PTT {$board} 板搜尋標題「{$pattern}」",
            'desc' => $feed->title,
            'link' => $feed->link->attributes()["href"],
            'items' => $items,
        ];
    }
}
