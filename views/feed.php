<?php
// RSS 2.0 feed – rendered without layout wrapper (render_raw)
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?= esc(APP_NAME) ?></title>
    <link><?= esc(base_url('')) ?></link>
    <description>Latest news from <?= esc(APP_NAME) ?></description>
    <language>en-au</language>
    <lastBuildDate><?= date(DATE_RSS) ?></lastBuildDate>
    <atom:link href="<?= esc(base_url('feed.xml')) ?>" rel="self" type="application/rss+xml"/>
    <?php foreach ($articles as $art): ?>
    <item>
      <title><?= esc($art['title']) ?></title>
      <link><?= esc(base_url('article/' . $art['id'])) ?></link>
      <guid isPermaLink="true"><?= esc(base_url('article/' . $art['id'])) ?></guid>
      <pubDate><?= date(DATE_RSS, (int) strtotime($art['published_at'])) ?></pubDate>
      <category><?= esc($art['category_name']) ?></category>
      <description><![CDATA[<?= mb_substr(strip_tags($art['body']), 0, 500) ?>]]></description>
    </item>
    <?php endforeach; ?>
  </channel>
</rss>
