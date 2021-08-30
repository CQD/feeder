<?php

namespace Q\Feeder\Controller;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise;

abstract class ControllerBase
{
    protected $httpCache = [];

    public function run($params)
    {
        try {
            $data = $this->logic($params);
            ob_start();
            include __DIR__ . '/rss_template.php';
            $body = ob_get_clean();

            header("ETag: " . md5($body));
            header('Cache-Control: public, max-age=3600');
            echo $body;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            http_response_code($e->getCode());
            $body = $e->getResponse()->getBody();
            if ($body = json_decode($body, true)) {
                echo $body['message'] ?? $e->getCode(), "\n";
            }
            error_log($e);
        } catch (\Exception $e) {
            http_response_code(500);
            echo "有東西爛掉了...\n";
            error_log($e);
        }
    }

    protected function get($url, $query = [], $options = []):? array
    {
        $body = $this->getRaw($url, $query, $options);
        return json_decode($body, true);
    }

    protected function getRaw($url, $query = [], $options = []):? string
    {
        $cacheKey = $url . '|' . json_encode($query);
        if ($cachedData = $this->httpCache[$cacheKey] ?? null) {
            return $cachedData;
        }

        $options += [
            'request.options' => [
                'exceptions' => false,
            ],
        ];

        $http = new HttpClient($options);
        $response = $http->get($url, [
            'query' => $query,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/92.0.4515.90 Mobile/15E148 Safari/604.1',
            ],
        ]);

        $body = $response->getBody();
        $this->httpCache[$cacheKey] = $body;
        return $body;
    }

    protected function getMulti($reqs, $options = [])
    {
        $options += [
            'request.options' => [
                'exceptions' => false,
            ],
        ];
        $http = new HttpClient($options);
        $http->setDefaultOption('headers', [
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/92.0.4515.90 Mobile/15E148 Safari/604.1',
        ]);
        $promises = [];
        foreach ($reqs as $req) {
            $url = is_string($req) ? $req : $req['url'];
            $query = is_string($req) ? [] : ($req['query'] ?? []);
            $cacheKey = $url . '|' . json_encode($query);
            if (isset($this->httpCache[$cacheKey])) {
                continue;
            }

            $promises[$cacheKey] = $http->getAsync($url, [
                'query' => $query,
            ]);
        }

        $results = Promise\settle($promises)->wait();

        $datas = [];
        foreach ($results as $cacheKey => $result) {
            $body = $result['value']->getBody();
            $datas[$cacheKey] = $this->httpCache[$cacheKey] = json_decode($body, true);
            $datas[$cacheKey]['_extra'] = $result;
        }

        return $datas;
    }

    protected function readCredential(string $name) : ?array
    {
        $file = __DIR__ . "/../../credential/{$name}.json";
        if (!is_file($file)) {
            throw new \Exception("Credential file `{$name}.json` not found!");
        }
        return json_decode(file_get_contents($file), true) ?: null;
    }

    abstract public function logic(array $params):array;
}
