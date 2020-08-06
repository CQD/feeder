<?php

namespace Q\Feeder\Controller;

class VocusUserController extends ControllerBase
{
    public function logic(array $params): array
    {
        ["user" => $user] = $params;

        $user = $this->get(sprintf('https://api.sosreader.com/api/users/%s', urlencode($user)));

        $data = $this->get('https://api.sosreader.com/api/articles', [
            'order' => 'desc',
            'sort' => 'lastPublishAt',
            'page' => 1,
            'num' => 10,
            'status' => 2,
            'userId' => $user['_id'],
        ]);

        return [
            'title' => "{$user['fullname']} / Vocus 作者",
            'desc' => strip_tags($user['intro']),
            'link' => sprintf("https://vocus.cc/user/@%s",
                urlencode($user['username'])
            ),
            'items' => array_map(function($article) use ($user){
                $url = sprintf('https://vocus.cc/@%s/%s', $user['username'], $article['_id']);
                return [
                    'title' => $article['title'],
                    'url' => $url,
                    'body' => nl2br($article['abstract']),
                    'guid' => $url,
                    'time' => strtotime($article['lastPublishAt']),
                ];
            }, $data['articles']),
        ];
    }



}
