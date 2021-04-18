<?php

namespace Q\Feeder\Controller;

use Qlurk\ApiClient as Qlurk;

class PlurkSearchController extends ControllerBase
{
    public function logic(array $params): array
    {
        $credential = require __DIR__ . '/../../credential/plurk.php';
        $qlurk = new Qlurk(
            $credential['appkey'],
            $credential['appsecret'],
            $credential['tokenkey'],
            $credential['tokensecret']
        );

        ["keyword" => $keyword] = $params;
        $keyword = urldecode($keyword);

        $resp = $qlurk->call('/APP/PlurkSearch/search', ['query' => $keyword]);

        $users = $resp['users'] ?? [];
        $plurks = $resp['plurks'] ?? [];

        $plurks = array_filter($plurks, function ($plurk) {
            return null === ($plurk['limited_to'] ?? null);
        });

        return [
            'lang' => 'zh-TW',
            'title' => "噗浪關鍵字搜尋：「{$keyword}」",
            'desc' => "噗浪上搜尋「{$keyword}」的最新結果",
            'link' => sprintf("https://www.plurk.com/search?q=%s", urlencode($keyword)),
            'items' => array_map(function($plurk) use ($users) {
                $userId = $plurk['user_id'];
                $nickName = $users[$userId]['nick_name'] ?? sprintf('[%s]', $userId);

                $content = $plurk['content_raw'] ?? '???';
                $title = mb_substr($content, 0, 30);

                $url = sprintf('https://www.plurk.com/p/%s', $this->base62Enc($plurk['plurk_id']));
                return [
                    'title' => sprintf('%s | %s%s', $nickName, str_replace("\n", " ", $title), ($title === $content) ? '' : '...'),
                    'url' => $url,
                    'body' => $plurk['content'] ?? $content ?? '???',
                    'guid' => $url,
                    'time' => strtotime($plurk['posted']),
                ];
            }, $plurks),
        ];
    }

    public function base62Enc($orig)
    {
        $pool = "0123456789abcdefghijklmnopqrstuvwxyz";
        $pool_len = strlen($pool);

        $result = '';
        while ($orig > 0) {
            $remain = $orig % $pool_len;
            $result .= $pool[$remain];
            $orig -= $remain;
            $orig /= $pool_len;
        }
        return strrev($result);
    }
}
