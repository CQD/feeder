<?php

namespace Q\Feeder\Controller;

class IssueCommentController extends ControllerBase
{
    public function logic(array $params): array
    {
        list( 'user' => $user, 'repo' => $repo) = $params;
        $url = sprintf(
            'https://api.github.com/repos/%s/%s/issues/comments',
            urlencode($user),
            urlencode($repo)
        );

        $data = $this->get($url, [
            'direction' => 'desc',
            'sort' => 'created_at',
        ]);

        $issueReqs = array_map(function($comment){
            return $comment['issue_url'];
        }, $data);

        return [
            'title' => "Github 上 {$user}/{$repo} 的最新 issue 回應",
            'desc' => "Github 上 {$user}/{$repo} 的最新 issue 回應",
            'link' => sprintf("https://github.com/%s/%s/issues",
                urlencode($user),
                urlencode($repo)
            ),
            'items' => array_map(function($comment){
                $issue = $this->get($comment['issue_url']) ?: [];
                return [
                    'title' => "Re: " . ($issue['title'] ?: '?????'),
                    'url' => $comment['html_url'],
                    'body' => nl2br($comment['body']),
                    'guid' => $comment['html_url'],
                    'time' => strtotime($comment['created_at']),
                ];
            }, $data),
        ];
    }
}
