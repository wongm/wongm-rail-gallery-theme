<?php

DEFINE ('ARCHIVE_URL_PATH', "/page/archive");
DEFINE ('SEARCH_URL_PATH', "/page/search");
DEFINE ('EVERY_ALBUM_PATH', "/page/everything");
DEFINE ('CONTACT_URL_PATH', "/contact.php");
DEFINE ('RANDOM_ALBUM_PATH', "/page/random");

DEFINE ('UPDATES_URL_PATH', "/page/recent-uploads");
DEFINE ('WAGON_UPDATES_URL_PATH', "/page/recent-wagons");
DEFINE ('BUSES_UPDATES_URL_PATH', "/page/recent-buses");
DEFINE ('TRAMS_UPDATES_URL_PATH', "/page/recent-trams");

DEFINE ('RECENT_ALBUM_PATH', "/page/recent-albums");

DEFINE ('ALL_TIME_URL_PATH', "/page/popular-all-time");
DEFINE ('THIS_MONTH_URL_PATH', "/page/popular-this-month");
DEFINE ('THIS_WEEK_URL_PATH', "/page/popular-this-week");
DEFINE ('RATINGS_URL_PATH', '/page/popular-by-ratings');
DEFINE ('POPULAR_URL_PATH', "/page/popular");
DEFINE ('DO_RATINGS_URL_PATH', '/page/rate-my-photos');
DEFINE ('GALLERY_PATH', '');

DEFINE ('RATINGS_TEXT', 'You can rate photos <a href="' . DO_RATINGS_URL_PATH . '">here</a>');

$popularImageText['all-time']['url'] = ALL_TIME_URL_PATH;
$popularImageText['all-time']['title'] = 'Popular photos - Most viewed of all time';
$popularImageText['all-time']['text'] = 'Most viewed of all time';
$popularImageText['all-time']['type'] = 'popular';
$popularImageText['all-time']['order'] = "i.hitcounter DESC";
$popularImageText['all-time']['where'] = "i.hitcounter > " . getOption('popular_threshold_hitcounter');

$popularImageText['this-month']['url'] = THIS_MONTH_URL_PATH;
$popularImageText['this-month']['title'] = 'Popular photos - Most viewed this month';
$popularImageText['this-month']['text'] = 'Most viewed this month';
$popularImageText['this-month']['type'] = 'popular';
$popularImageText['this-month']['order'] = "i.hitcounter_month DESC";
$popularImageText['this-month']['where'] = "i.hitcounter_month > " . getOption('popular_threshold_hitcounter');

$popularImageText['this-week']['url'] = THIS_WEEK_URL_PATH;
$popularImageText['this-week']['title'] = 'Popular photos - Most viewed this week';
$popularImageText['this-week']['text'] = 'Most viewed this week';
$popularImageText['this-week']['type'] = 'popular';
$popularImageText['this-week']['order'] = "i.hitcounter_week DESC";
$popularImageText['this-week']['where'] = "i.hitcounter_week > " . getOption('popular_threshold_hitcounter');

$popularImageText['ratings']['url'] = RATINGS_URL_PATH;
$popularImageText['ratings']['title'] = 'Popular photos - Highest rated';
$popularImageText['ratings']['text'] = 'Highest rated';
$popularImageText['ratings']['type'] = 'popular';
$popularImageText['ratings']['order'] = "i.ratings_score DESC, i.hitcounter DESC";
$popularImageText['ratings']['where'] = "i.ratings_view > " . getOption('popular_threshold_hitcounter');

$popularImageText['uploads']['url'] = UPDATES_URL_PATH;
$popularImageText['uploads']['title'] = 'Recent uploads';
$popularImageText['uploads']['subtext'] = 'For recent wagon photos go to the <a href="' . WAGON_UPDATES_URL_PATH . '" title="wagons and containers page">wagons and containers page</a>.';

$popularImageText['resize']['title'] = 'Images to resize';
$popularImageText['resize']['text'] = 'Images to be resized to standard aspect ratio';

$popularImageText['shrink']['title'] = 'Images to shrink';
$popularImageText['shrink']['text'] = 'Images to be shrunk to standard aspect ratio';

$popularImageText['shrink-albums']['title'] = 'Images to shrink, by album';
$popularImageText['shrink-albums']['text'] = 'Images to be shrunk to standard aspect ratio, ordered by album';

$popularImageText['uncaptioned']['title'] = 'Images to caption';

$popularImageText['uncaptioned-albums']['title'] = 'Albums with images to caption';

$popularImageText['duplicates']['title'] = 'Duplicated images';

$popularImageText['wagons']['url'] = WAGON_UPDATES_URL_PATH;
$popularImageText['wagons']['title'] = 'Recent uploads - Wagons';
$popularImageText['wagons']['text'] = 'Recent wagons and container uploads';
$popularImageText['wagons']['type'] = 'recent';
$popularImageText['wagons']['order'] = "i.mtime DESC";
$popularImageText['wagons']['where'] = "folder LIKE 'wagons%'";
$popularImageText['wagons']['subtext'] = 'Sorted by upload date to the site (not when they were taken).';

?>