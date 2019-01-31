<?php

// format:
// - title
// - desc
// - link
// - items
//   - title
//   - link
//   - body
//   - guid
//   - time (unix epoch)
extract($data);

/////////////////////////////////

function timeFormat($time)
{
    return date('r', $time);
}

echo '<' . '?';  ?>xml version='1.0' encoding='UTF-8'<?php echo '?'.'>' ?>
<rss version='2.0'>
<channel>

<title><?= e($title ?? "CQD's aggregated RSS") ?></title>
<link><?= e($link ?? '') ?></link>
<description><?= e($desc ?? "CQD's aggregated RSS") ?></description>
<language><?= e($lang ?? 'en-us')?></language>

<?php foreach ($items as $item): ?>
<item>
<title><?= e($item['title'] ?? '') ?></title>
<link><?= e($item['url'] ?? '') ?></link>
<description><?= e($item['body'] ?? '') ?></description>
<guid><?= e($item['guid'] ?? '') ?></guid>
<pubDate><?= e(timeFormat( $item['time'] ?? time() )) ?></pubDate>
</item>

<?php endforeach; ?>

</channel>
</rss>
