<?php

namespace Q\Feeder\Controller;

class IndexController extends ControllerBase
{
    public function run($params)
    {
        header('Content-Type: text/plain');
        echo <<<EOT
# CQD 的 Feed 產生器

有些東西沒有 RSS Feed，但是我想訂閱，所以自己寫。


## Github

- /github/repo/{user}/{repo}/issuecomment
  - 某個 Repo 的所有 Issue/PR 的回應
  - 例如：https://feed.cqd.tw/github/repo/composer/composer/issuecomment

EOT;
    }

    public function logic(array $params): array
    {
        return [];
    }
}
