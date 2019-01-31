<?php

namespace Q\Feeder\Controller;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise;

abstract class ControllerBase
{
    protected $httpCache = [];
    protected $credential = null;

    public function run($params)
    {
        global $data;
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

    protected function get($url, $query = []):? array
    {
        $this->prepareCredential();

        $cacheKey = $url . '|' . json_encode($query);
        if ($cachedData = $this->httpCache[$cacheKey] ?? null) {
            return $cachedData;
        }

        $http = new HttpClient([
            'auth' => [
                $this->credential['github']['user'],
                $this->credential['github']['token'],
            ],
        ]);
        $response = $http->get($url, [
            'query' => $query,
        ]);

        $body = $response->getBody();
        $data = $this->httpCache[$cacheKey] = json_decode($body, true);
        return $data;
    }

    protected function getMulti($reqs)
    {
        $this->prepareCredential();

        $http = new HttpClient([
            'auth' => [
                $this->credential['github']['user'],
                $this->credential['github']['token'],
            ],
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
        }

        return $datas;
    }

    protected function prepareCredential()
    {
        if ($this->credential) {
            return;
        }
        $this->credential = [];

        $list = [
            'github',
        ];
        foreach ($list as $name) {
            $file = __DIR__ . "/../../credential/{$name}";
            if (!is_file($file)) {
                throw new \Exception("Credential file for `{$name}` not found!");
            }
            $this->credential[$name] = json_decode(file_get_contents($file), true);
        }
    }

    abstract public function logic(array $params):array;
}
