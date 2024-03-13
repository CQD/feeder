<?php

namespace Q\Feeder\Controller;

class FiveNineOneSaleController extends ControllerBase
{
    public function logic(array $params): array
    {
        ["id" => $id] = $params;

        $detail = $this->get('https://api.591.com.tw/tw/v1/community/detail', ['id' => $id]);
        $sales = $this->get('https://api.591.com.tw/tw/v1/community/sales', [
            'community_id' => $id,
            'page' => 1,
        ]);

        $comu = $detail['data']['community'] ?? [];

        return [
            'lang' => 'zh-TW',
            'title' => sprintf("%s%s %s (%s)",
                $comu['region'] ?? '某縣市',
                $comu['section'] ?? '某區域',
                $comu['name'] ?? '某建案',
                $comu['const_company'] ?? '某建設公司'
            ),
            'desc' => sprintf("%s\n%s, %s, %s", $comu['address'], $comu['age'], $comu['floor'], $comu['house_holds']),
            'link' => sprintf("https://market.591.com.tw/%d", $id),
            'items' => array_map(function($sale) use ($comu){
                $url = sprintf('https://sale.591.com.tw/home/house/detail/2/%d.html', $sale['houseid']);
                $timeFromImage = $this->timeFromImage($sale['photo_src']);

                $bodyPattern = <<<EOT
<img src="%s"><br>
<h1>%s</h1>
總價：%s%s<br>
單價：%s<br>
配置：%s %s %s%s<br>
地址：%s<br>
圖片時間：%s<br>
EOT;
                $body = sprintf($bodyPattern,
                    e($sale['photo_src']),
                    e($sale['title']),
                    e($sale['price_v']['price']),
                    e($sale['price_v']['unit']),
                    e($sale['price_unit']),
                    e($sale['floor']),
                    e($sale['room']),
                    e($sale['area_v']['area']),
                    e($sale['area_v']['unit']),
                    e($sale['address']),
                    $timeFromImage ? date('Y-m-d H:i:s', (int) $timeFromImage) : '未知'
                );
                return [
                    'title' => sprintf("[%s] %s | %s | %s%s | %s%s",
                        $comu['name'],
                        $sale['floor'],
                        $sale['room'],
                        $sale['area_v']['area'],
                        $sale['area_v']['unit'],
                        $sale['price_v']['price'],
                        $sale['price_v']['unit'],
                    ),
                    'url' => $url,
                    'body' => $body,
                    'guid' => $url,
                    'time' => $timeFromImage ?: time(),
                ];
            }, $sales['data']),
        ];
    }


    private function timeFromImage(string $url)
    {
        $hit = preg_match('/[0-9]{18}/', $url, $matches);
        if (!$hit) {
            return null;
        }

        return $matches[0] / 100000000;
    }
}
