<?php
/**
 * Merge multiple RSS feeds to one and display them on a web page
 *
 * https://geekylifestyle.com/best-way-merge-multiple-rss-feeds-with-php
 *
 * @package geekylifestyle
 */

$rss = new DOMDocument();
$feed = array();
$urlarray = array(
  array( 'name' => 'From my blog',          'url' => 'https://wongm.com/category/trains,trams/feed/' ),
  array( 'name' => 'Tales from Europe',     'url' => 'https://www.eurogunzel.com/category/trains,trams/feed/' ),
  array( 'name' => 'Tales from Hong Kong',  'url' => 'https://www.checkerboardhill.com/category/transport/feed/' ),
);

foreach ( $urlarray as $url ) {
  $rss->load( $url['url'] );

  foreach ( $rss->getElementsByTagName( 'item' ) as $node ) {
	if(is_object($node->getElementsByTagName( 'title' )->item( 0 ))) {
	  $item = array(
		'site'  => $url['name'],
		'title' => $node->getElementsByTagName( 'title' )->item( 0 )->nodeValue,
		'description'  => $node->getElementsByTagName( 'description' )->item( 0 )->nodeValue,
		'link'  => $node->getElementsByTagName( 'link' )->item( 0 )->nodeValue,
		'pubDate'  => $node->getElementsByTagName( 'pubDate' )->item( 0 )->nodeValue,
		'guid'  => $node->getElementsByTagName( 'guid' )->item( 0 )->nodeValue,
		'content'  => $node->getElementsByTagNameNs( 'http://purl.org/rss/1.0/modules/content/', 'encoded' )->item( 0 )->nodeValue,
	  );
	}

  array_push( $feed, $item );
  }
}

usort( $feed, function( $a, $b ) {
  return strtotime( $b['pubDate'] ) - strtotime( $a['pubDate'] );
});

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	xmlns:georss="http://www.georss.org/georss"
	xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
	>
	<channel>
		<title>Transport related blog posts by Marcus Wong</title>
		<atom:link href="https://railgallery.wongm.com/page/rss-transport-blogs" rel="self" type="application/rss+xml" />
		<description>Transport related blog posts by Marcus Wong</description>
		<lastBuildDate><?php echo date('r', time()); ?></lastBuildDate>
		<language>en-AU</language>
		<sy:updatePeriod>hourly</sy:updatePeriod>
		<sy:updateFrequency>1</sy:updateFrequency>
		<generator>ZenPhoto RSS Merger</generator>
<?php

$limit = 30;
for ( $x = 0; $x < $limit; $x++ ) {
    $site = $feed[ $x ]['site'];
    $title = str_replace( ' & ', ' & ', $feed[ $x ]['title'] );
    $link = $feed[ $x ]['link'];
    $description = $feed[ $x ]['description'];
    $pubDate = $feed[ $x ]['pubDate'];
    $guid = $feed[ $x ]['guid'];
    $content = $feed[ $x ]['content'];
?>
<item>
    <title><?php echo $site; ?>: <?php echo $title; ?></title>
    <link><?php echo $link; ?></link>
    <description><![CDATA[<?php echo $description; ?>]]></description>
    <content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
    <guid isPermaLink="false"><?php echo $guid; ?></guid>
    <pubDate><?php echo $pubDate; ?></pubDate>
</item>
<?php
}
?>
</channel>
</rss>