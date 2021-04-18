<?php

namespace Q\Feeder\Controller;

class VocusPublicationController extends ControllerBase
{
    public function logic(array $params): array
    {
        ["id" => $id] = $params;

        $publication = $this->get(sprintf('https://api.sosreader.com/api/publication/%s', urlencode($id)));

        $data = $this->get('https://api.sosreader.com/api/articles', [
            'order' => 'desc',
            'sort' => 'lastPublishAt',
            'page' => 1,
            'num' => 10,
            'status' => 2,
            'publicationId' => $publication['_id'],
        ]);

        return [
            'lang' => 'zh-TW',
            'title' => "{$publication['title']} / Vocus 專題",
            'desc' => strip_tags($publication['abstract']),
            'link' => sprintf("https://vocus.cc/%s/home",
                urlencode($publication['urlId'])
            ),
            'items' => array_map(function($article) use ($publication){
                $url = sprintf('https://vocus.cc/%s/%s', $publication['urlId'], $article['_id']);
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
