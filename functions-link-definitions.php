<?php

DEFINE ('HOME_PATH', WEBPATH . "/");
DEFINE ('NEWS_URL_PATH', WEBPATH . "/news");

DEFINE ('ARCHIVE_URL_PATH', WEBPATH . "/page/archive");
DEFINE ('SEARCH_URL_PATH', WEBPATH . "/page/search");
DEFINE ('EVERY_ALBUM_PATH', WEBPATH . "/page/everything");
DEFINE ('ALBUM_THEME_PATH', WEBPATH . "/page/albums");
DEFINE ('CONTACT_URL_PATH', WEBPATH . "/contact.php");
DEFINE ('RANDOM_ALBUM_PATH', WEBPATH . "/page/random");

DEFINE ('UPDATES_URL_PATH', WEBPATH . "/page/recent-uploads");
DEFINE ('WAGON_UPDATES_URL_PATH', WEBPATH . "/page/recent-wagons");
DEFINE ('BUSES_UPDATES_URL_PATH', WEBPATH . "/page/recent-buses");
DEFINE ('TRAMS_UPDATES_URL_PATH', WEBPATH . "/page/recent-trams");
DEFINE ('TRAINS_UPDATES_URL_PATH', WEBPATH . "/page/recent-trains");

DEFINE ('RECENT_ALBUM_PATH', WEBPATH . "/page/recent-albums");

DEFINE ('ALL_TIME_URL_PATH', WEBPATH . "/page/popular-all-time");
DEFINE ('THIS_YEAR_URL_PATH', WEBPATH . "/page/popular-this-year");
DEFINE ('THIS_MONTH_URL_PATH', WEBPATH . "/page/popular-this-month");
DEFINE ('THIS_WEEK_URL_PATH', WEBPATH . "/page/popular-this-week");
DEFINE ('RATINGS_URL_PATH', WEBPATH . '/page/popular-by-ratings');
DEFINE ('POPULAR_URL_PATH', WEBPATH . "/page/popular");
DEFINE ('DO_RATINGS_URL_PATH', WEBPATH . '/page/rate-my-photos');
DEFINE ('ON_THIS_DAY_URL_PATH', WEBPATH . '/page/on-this-day');
DEFINE ('GALLERY_PATH', '');

DEFINE ('RATINGS_TEXT', 'You can rate photos <a href="' . DO_RATINGS_URL_PATH . '">here</a>');

DEFINE ('EXCLUDED_WAGONS_SQL', "a.folder NOT LIKE 'wagons%'");
DEFINE ('BUS_ALBUM_IDs_SQL', "SELECT `objectid` FROM ". prefix('obj_to_tag') ." ott INNER JOIN ". prefix('tags') ." t ON ott.`tagid` = t.`id` WHERE ott.`type` = 'albums' AND t.`name` = 'buses'");
DEFINE ('EXCLUDED_IMAGE_ALBUM_SQL', EXCLUDED_WAGONS_SQL . " AND a.folder NOT LIKE '%stations%' AND a.folder NOT LIKE '%infrastructure%' AND a.folder NOT LIKE '%bits%' AND a.folder != 'photoshop' AND a.id NOT IN (" . BUS_ALBUM_IDs_SQL . ") AND a.folder NOT LIKE '%interiors%'");

DEFINE ('UNCAPTIONED_IMAGE_REGEX', "i.title REGEXP '_[0-9]{4}' OR i.title REGEXP 'DSCF[0-9]{4}' OR i.title = '' OR i.title  IS NULL");
DEFINE ('CAPTIONED_IMAGE_REGEX', "i.title NOT REGEXP '_[0-9]{4}' AND i.title NOT REGEXP 'DSCF[0-9]{4}'");
DEFINE ('IMAGE_NEEDING_RESIZE_SQL', "((i.height = 1024 AND i.width != 683) OR (i.width = 1024 AND i.height != 683) OR (i.height = 1920 AND i.width != 1280) OR (i.width = 1920 AND i.height != 1280)) AND " . EXCLUDED_WAGONS_SQL);
DEFINE ('IMAGE_NEEDING_SHRINK_SQL', "(i.height = 1024 AND i.width > 683) OR (i.width = 1024 AND i.height > 683) OR (i.height = 1920 AND i.width > 1280) OR (i.width = 1920 AND i.height > 1280) AND " . EXCLUDED_WAGONS_SQL);

$popularImageText['all-time']['url'] = ALL_TIME_URL_PATH;
$popularImageText['all-time']['title'] = 'Popular photos - Most viewed of all time';
$popularImageText['all-time']['text'] = 'Most viewed of all time';
$popularImageText['all-time']['type'] = 'popular';
$popularImageText['all-time']['order'] = "i.hitcounter DESC";
$popularImageText['all-time']['where'] = "i.hitcounter > " . getOption('popular_threshold_hitcounter');

$popularImageText['this-year']['url'] = THIS_YEAR_URL_PATH;
$popularImageText['this-year']['title'] = 'Popular photos - Most viewed this year';
$popularImageText['this-year']['text'] = 'Most viewed this year';
$popularImageText['this-year']['type'] = 'popular';
$popularImageText['this-year']['order'] = "i.hitcounter_year DESC";
$popularImageText['this-year']['where'] = "i.hitcounter_year > " . getOption('popular_threshold_hitcounter');

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

$popularImageText['resize']['title'] = 'Images to resize';
$popularImageText['resize']['text'] = 'Images to be resized to standard aspect ratio';
$popularImageText['resize']['internal'] = true;

$popularImageText['shrink']['title'] = 'Images to shrink';
$popularImageText['shrink']['text'] = 'Images to be shrunk to standard aspect ratio';
$popularImageText['shrink']['internal'] = true;

$popularImageText['shrink-albums']['title'] = 'Images to shrink, by album';
$popularImageText['shrink-albums']['text'] = 'Images to be shrunk to standard aspect ratio, ordered by album';
$popularImageText['shrink-albums']['internal'] = true;

$popularImageText['uncaptioned']['title'] = 'Images to caption';
$popularImageText['uncaptioned']['internal'] = true;

$popularImageText['uncaptioned-albums']['title'] = 'Albums with images to caption';
$popularImageText['uncaptioned-albums']['internal'] = true;

$popularImageText['duplicates']['title'] = 'Duplicated images';
$popularImageText['duplicates']['internal'] = true;

$popularImageText['wagons']['url'] = WAGON_UPDATES_URL_PATH;
$popularImageText['wagons']['title'] = 'Recent uploads - Wagons';
$popularImageText['wagons']['text'] = 'Recent wagons and container uploads';
$popularImageText['wagons']['type'] = 'recent';
$popularImageText['wagons']['order'] = "i.mtime DESC";
$popularImageText['wagons']['where'] = "folder LIKE 'wagons%'";
$popularImageText['wagons']['subtext'] = 'Sorted by upload date to the site (not when they were taken).';

$popularImageText['trains']['url'] = TRAINS_UPDATES_URL_PATH;
$popularImageText['trains']['title'] = 'Recent uploads - Trains';
$popularImageText['trains']['text'] = 'Recent train uploads';
$popularImageText['trains']['subtext'] = 'Trains and railway infrastructure';
$popularImageText['trains']['type'] = 'recent';
$popularImageText['trains']['where'] = "folder NOT LIKE '%tram%' AND folder NOT LIKE '%light-rail%' AND " . EXCLUDED_WAGONS_SQL. " AND a.id NOT IN (" . BUS_ALBUM_IDs_SQL . ")";

$popularImageText['trams']['url'] = TRAMS_UPDATES_URL_PATH;
$popularImageText['trams']['title'] = 'Recent uploads - Trams';
$popularImageText['trams']['text'] = 'Recent tram uploads';
$popularImageText['trams']['subtext'] = 'Trams, tram stops and tramway infrastructure';
$popularImageText['trams']['type'] = 'recent';
$popularImageText['trams']['where'] = "folder LIKE '%tram%' OR folder LIKE '%light-rail%'";

$popularImageText['buses']['url'] = BUSES_UPDATES_URL_PATH;
$popularImageText['buses']['title'] = 'Recent uploads - Buses';
$popularImageText['buses']['text'] = 'Recent bus uploads';
$popularImageText['buses']['subtext'] = 'Buses, bus stops and bus depots';
$popularImageText['buses']['type'] = 'recent';
$popularImageText['buses']['where'] = "a.id IN (" . BUS_ALBUM_IDs_SQL . ")";

?>