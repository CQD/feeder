<?php declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use Q\Feeder\Router;

final class BasicFeedTest extends TestCase
{
    public static function feedProvider()
    {
        $paths = [
            '/ptt/Gossiping/title/Re',
            '/vocus/user/sophist4ever',
            '/vocus/publication/sophist4ever',
            '/plurk/search/愛情',
            '/tepa/epaper',
            '/591/comu/4055',
            '/github/repo/composer/composer/issuecomment',
        ];

        $rows = [];
        foreach ($paths as $path) {
            $rows[$path] = [$path];
        }

        return $rows;
    }

    #[DataProvider('feedProvider')]
    public function testFeedHasContent($path): void
    {
        list($controller, $params) = Router::route($path);
        $this->assertNotNull($controller);

        $result = (new $controller)->logic($params);
        $this->assertIsArray($result["items"]);
        $this->assertNotEmpty($result["items"]);
    }
}