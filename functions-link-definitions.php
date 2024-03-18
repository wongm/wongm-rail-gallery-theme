<?php

DEFINE ('HOME_PATH', WEBPATH . "/");
DEFINE ('NEWS_URL_PATH', WEBPATH . "/news");

DEFINE ('ARCHIVE_URL_PATH', WEBPATH . "/page/archive");
DEFINE ('SEARCH_URL_PATH', WEBPATH . "/page/search");
DEFINE ('EVERY_ALBUM_PATH', WEBPATH . "/page/everything");
DEFINE ('ALBUM_THEME_PATH', WEBPATH . "/page/albums");
DEFINE ('CONTACT_URL_PATH', WEBPATH . "/contact.php");
DEFINE ('RANDOM_ALBUM_PATH', WEBPATH . "/page/random");
DEFINE ('FLEETLISTS_URL_PATH', WEBPATH . "/page/fleetlists");

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
global $_zp_db;
DEFINE ('BUS_ALBUM_IDs_SQL', "SELECT `objectid` FROM ". $_zp_db->prefix('obj_to_tag') ." ott INNER JOIN ". $_zp_db->prefix('tags') ." t ON ott.`tagid` = t.`id` WHERE ott.`type` = 'albums' AND t.`name` = 'buses'");
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

$popularImageText['nr-class']['url'] = 'every-nr-class';
$popularImageText['nr-class']['title'] = 'Every single NR class locomotive';
$popularImageText['nr-class']['subtext'] = 'Every single NR class locomotive from NR1 to NR122 (minus NR3 and NR33 because I never photographed them before they were scrapped)';
$popularImageText['nr-class']['maxcount'] = 122;
$popularImageText['nr-class']['type'] = 'fleetlist';
$popularImageText['nr-class']['order'] =  "CONVERT(REPLACE(REGEXP_SUBSTR (i.title, '^*NR+[0-9]*'), 'NR', ''), DECIMAL)";
$popularImageText['nr-class']['where'] = "i.id IN (SELECT id FROM
	(
	select  distinct 
		i.id,
		i.title,
		filename,
		folder,
		ROW_NUMBER() OVER (PARTITION BY REGEXP_SUBSTR (i.title, '^*NR{1,3}[0-9]*') ORDER BY i.hitcounter DESC) AS RowNumber,
		CONVERT(REPLACE(REGEXP_SUBSTR (i.title, '^*NR+[0-9]*'), 'NR', ''), DECIMAL) AS locoID 
		from zen_images i
		join zen_albums ON i.albumid = zen_albums.id
	where i.title REGEXP  'NR{1,3}[0-9]'
	AND zen_albums.folder NOT LIKE '%bits%' AND zen_albums.folder NOT LIKE '%vline%'
	) as trains
	WHERE RowNumber = 1 AND locoID <> 0)";

$popularImageText['vlocity']['url'] = 'every-vlocity';
$popularImageText['vlocity']['title'] = 'Every single V/Line VLocity railcar';
$popularImageText['vlocity']['subtext'] = 'Every single V/Line VLocity railcar from VL00 up to whatever is the current highest number - now VL100 and upwards!';
$popularImageText['vlocity']['maxcount'] = 300;
$popularImageText['vlocity']['type'] = 'fleetlist';
$popularImageText['vlocity']['order'] =  "CONVERT(REPLACE(REGEXP_SUBSTR (REPLACE(REPLACE(i.title, 'VS', 'VL'), 'VLocity', ''), '^*VL+[0-9]*'), 'VL', ''), DECIMAL)";
$popularImageText['vlocity']['where'] = "i.id IN (SELECT id FROM
(
select  distinct 
	i.id,
	i.title,
	filename,
	folder,
	ROW_NUMBER() OVER (PARTITION BY REGEXP_SUBSTR (REPLACE(REPLACE(i.title, 'VS', 'VL'), 'VLocity', ''), '^*VL{1,3}[0-9]*') ORDER BY i.hitcounter DESC) AS RowNumber,
	REGEXP_SUBSTR (REPLACE(REPLACE(i.title, 'VS', 'VL'), 'VLocity', ''), '^*VL{1,3}[0-9]*') AS loco, 
	CONVERT(REPLACE(REGEXP_SUBSTR (REPLACE(REPLACE(i.title, 'VS', 'VL'), 'VLocity', ''), '^*VL+[0-9]*'), 'VL', ''), DECIMAL) AS locoID 
	from zen_images i
	join zen_albums ON i.albumid = zen_albums.id
where (i.title REGEXP  'VL{1,3}[0-9]' OR i.title REGEXP  'VS{1,3}[0-9]')
AND zen_albums.folder NOT LIKE '%bits%' AND zen_albums.folder NOT LIKE '%interior%'  AND zen_albums.folder NOT LIKE '%issues%' AND zen_albums.folder NOT LIKE '%promotional%' AND zen_albums.folder LIKE 'vline%' AND filename NOT IN ('F140_8128.jpg')
HAVING locoID < 300
) as vlocitytrains
WHERE RowNumber = 1)";

$popularImageText['xtrapolis']['url'] = 'every-xtrapolis';
$popularImageText['xtrapolis']['title'] = 'Every single X\'Trapolis train';
$popularImageText['xtrapolis']['subtext'] = 'Every single X\'Trapolis train - from 851M to 966M in the first order, then 1M up to 288M and on to 967M-986M in the follow on orders';
$popularImageText['xtrapolis']['maxcount'] = 600;
$popularImageText['xtrapolis']['type'] = 'fleetlist';
$popularImageText['xtrapolis']['order'] =  "CONVERT(REPLACE(REGEXP_SUBSTR (i.title, '^*X''Trapolis +[0-9]*'), 'X''Trapolis ', ''), DECIMAL)";
$popularImageText['xtrapolis']['where'] = "i.id IN (SELECT id FROM
(
select  distinct 
	i.id,
	i.title,
	filename,
	folder,
	ROW_NUMBER() OVER (PARTITION BY REGEXP_SUBSTR (i.title, '^*X''Trapolis {1,3}[0-9]*') ORDER BY i.hitcounter DESC) AS RowNumber,
	CONVERT(REPLACE(REGEXP_SUBSTR (i.title, '^*X''Trapolis +[0-9]*'), 'X\'Trapolis ', ''), DECIMAL) AS locoID 
	from zen_images i
	join zen_albums ON i.albumid = zen_albums.id
where i.title REGEXP  'X''Trapolis {1,3}[0-9]'
AND zen_albums.folder NOT LIKE '%bits%' AND zen_albums.folder NOT LIKE '%vicers%' AND zen_albums.folder NOT LIKE '%accessibility%'
AND zen_albums.folder NOT LIKE '%radio%' AND zen_albums.folder NOT LIKE '%commercial%' AND zen_albums.folder NOT LIKE '%rebrand%'
 AND zen_albums.folder NOT LIKE '%trial%'   AND zen_albums.folder NOT LIKE '%interior%' AND zen_albums.folder NOT LIKE '%tram%' 
AND zen_albums.folder NOT LIKE '%vandals%'
AND zen_albums.folder NOT LIKE '%transfer%'
AND zen_albums.folder NOT LIKE '%hcmt%'
AND zen_albums.folder NOT LIKE '%new-xtraps%'
HAVING locoID > 0 AND locoID < 1000
) as XTrapolistrains
WHERE RowNumber = 1)";

$popularImageText['comeng']['url'] = 'every-comeng';
$popularImageText['comeng']['title'] = 'Every single Comeng train';
$popularImageText['comeng']['subtext'] = 'Every single Comeng train - from 301M to 698M';
$popularImageText['comeng']['maxcount'] = 600;
$popularImageText['comeng']['type'] = 'fleetlist';
$popularImageText['comeng']['where'] = "i.id IN (SELECT id FROM
(
select  distinct 
	i.id,
	i.title,
	filename,
	folder,
	ROW_NUMBER() OVER (PARTITION BY REGEXP_SUBSTR (i.title, '^*Comeng {1,3}[0-9]*') ORDER BY i.hitcounter DESC) AS RowNumber,
	REGEXP_SUBSTR (i.title, '^*Comeng {1,3}[0-9]M*') AS loco, 
	CONVERT(REPLACE(REGEXP_SUBSTR (i.title, '^*Comeng +[0-9]*'), 'Comeng ', ''), DECIMAL) AS locoID 
	from zen_images i
	join zen_albums ON i.albumid = zen_albums.id
where i.title REGEXP  'Comeng {1,3}[0-9]'
AND zen_albums.folder NOT LIKE '%bits%' AND zen_albums.folder NOT LIKE '%vicers%' AND zen_albums.folder NOT LIKE '%accessibility%'
AND zen_albums.folder NOT LIKE '%radio%' AND zen_albums.folder NOT LIKE '%commercial%' AND zen_albums.folder NOT LIKE '%rebrand%' AND zen_albums.folder NOT LIKE '%adelaide%'
 AND zen_albums.folder NOT LIKE '%trial%'   AND zen_albums.folder NOT LIKE '%interior%' AND zen_albums.folder NOT LIKE '%tram%' 
AND zen_albums.folder NOT LIKE '%vandals%' AND zen_albums.folder NOT LIKE '%stored%' AND zen_albums.folder NOT LIKE '%scrap%' 
AND zen_albums.folder !='comeng-life-extension-project'
HAVING locoID < 1000
) as comengtrains
WHERE RowNumber = 1)";
$popularImageText['comeng']['order'] =  "CONVERT(REPLACE(REGEXP_SUBSTR (i.title, '^*Comeng +[0-9]*'), 'Comeng ', ''), DECIMAL)";

$popularImageText['hcmt']['url'] = 'every-hcmt';
$popularImageText['hcmt']['title'] = 'Every single High Capacity Metro Train';
$popularImageText['hcmt']['subtext'] = 'Every single High Capacity Metro Train - from set 1 through to 70';
$popularImageText['hcmt']['maxcount'] = 70;
$popularImageText['hcmt']['type'] = 'fleetlist';
$popularImageText['hcmt']['order'] =  "CONVERT(REPLACE(REGEXP_SUBSTR (i.title, '^*HCMT set +[0-9]*'), 'HCMT set ', ''), DECIMAL)";
$popularImageText['hcmt']['where'] = "i.id IN (SELECT id FROM
(
select  distinct 
	i.id,
	i.title,
	filename,
	folder,
	ROW_NUMBER() OVER (PARTITION BY REGEXP_SUBSTR (i.title, '^*HCMT set {1,3}[0-9]*') ORDER BY i.hitcounter DESC) AS RowNumber,
	REGEXP_SUBSTR (i.title, '^*HCMT set {1,3}[0-9]M*') AS loco, 
	CONVERT(REPLACE(REGEXP_SUBSTR (i.title, '^*HCMT set +[0-9]*'), 'HCMT set ', ''), DECIMAL) AS locoID 
	from zen_images i
	join zen_albums ON i.albumid = zen_albums.id
where i.title REGEXP  'HCMT set {1,3}[0-9]'
) as hcmts
WHERE RowNumber = 1)";

$popularImageText['e-class']['url'] = 'every-e-class';
$popularImageText['e-class']['title'] = 'Every single E class tram';
$popularImageText['e-class']['subtext'] = 'Every single E class tram E.6001 to E2.6100';
$popularImageText['e-class']['maxcount'] = 122;
$popularImageText['e-class']['type'] = 'fleetlist';
$popularImageText['e-class']['order'] =  "CONVERT(REPLACE(REGEXP_SUBSTR (REPLACE(i.title, 'E2.', 'E.'), '^*E.+[0-9]*'), 'E.', ''), DECIMAL)";
$popularImageText['e-class']['where'] = "i.id IN (SELECT id FROM
(
select  distinct 
	i.id,
	i.title,
	filename,
	folder,
	ROW_NUMBER() OVER (PARTITION BY REGEXP_SUBSTR (REPLACE(i.title, 'E2.', 'E.'), '^*E.{1,3}[0-9]*') ORDER BY i.hitcounter DESC) AS RowNumber,
	CONVERT(REPLACE(REGEXP_SUBSTR (REPLACE(i.title, 'E2.', 'E.'), '^*E.+[0-9]*'), 'E.', ''), DECIMAL) AS locoID 
	from zen_images i
	join zen_albums ON i.albumid = zen_albums.id
where (i.title REGEXP  'E.{1,3}[0-9]' OR i.title REGEXP  'E2.{1,3}[0-9]')
AND zen_albums.folder NOT LIKE '%bits%' 
AND zen_albums.folder NOT LIKE '%motorists%'
AND zen_albums.folder NOT LIKE '%preston%'
) as nrclasses
WHERE RowNumber = 1 AND locoID <> 0)";

$popularImageText['bus']['url'] = 'every-bus';
$popularImageText['bus']['title'] = 'Every Victorian bus I\'ve photographed';
$popularImageText['bus']['subtext'] = 'Every single Victorian registered bus that I\'ve photographed - registration plates in the nnnnAO and the BSnnaa series';
$popularImageText['bus']['maxcount'] = 5000;
$popularImageText['bus']['type'] = 'fleetlist';
$popularImageText['bus']['order'] =  "REGEXP_SUBSTR (REPLACE(i.title, 'BS', 'BS99'), 'BS[0-9]{4}[A-Z]*'), CONVERT(REPLACE(REGEXP_SUBSTR (i.title, '[0-9]{4}AO*'), 'AO', ''), DECIMAL)";
$popularImageText['bus']['where'] = "i.id IN (SELECT id FROM
(
select  distinct 
	nr.id,
	nr.title,
	filename,
	folder,
	ROW_NUMBER() OVER (PARTITION BY REGEXP_SUBSTR (nr.title, '^*[0-9]{4}AO*') ORDER BY nr.hitcounter DESC) AS RowNumber,
	CONVERT(REPLACE(REGEXP_SUBSTR (nr.title, '^*[0-9]{4}AO*'), 'AO', ''), DECIMAL) AS busid 
	from zen_images nr
	join zen_albums ON nr.albumid = zen_albums.id
where (nr.title REGEXP  '[0-9]AO')
AND zen_albums.folder NOT LIKE '%bits%' 
UNION ALL
select  distinct 
	nr.id,
	nr.title,
	filename,
	folder,
	ROW_NUMBER() OVER (PARTITION BY REGEXP_SUBSTR (nr.title, '^*BS[0-9]{2}[A-Z]*') ORDER BY nr.hitcounter DESC) AS RowNumber,
	REPLACE(REGEXP_SUBSTR (nr.title, '^*BS[0-9]{2}[A-Z]*'), 'BS', '') AS busid 
	from zen_images nr
	join zen_albums ON nr.albumid = zen_albums.id
where (nr.title REGEXP  'BS[0-9]{2}[A-Z]')
AND zen_albums.folder NOT LIKE '%bits%' 
) as nrclasses
WHERE RowNumber = 1 AND busid <> 0)";

?>