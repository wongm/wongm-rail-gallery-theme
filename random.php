<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die();

$pageTitle = ' - Random photos';
include_once('header.php');
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;
	<a href="<?php echo RANDOM_ALBUM_PATH; ?>" title="Random Images">Random photos</a>
	</span><span id="righthead"><?php printSearchForm(); ?></span>
</div>
<div class="topbar">
	<h2>Random images</h2>
	<p>A selection of random photos each time you refresh the page</p>
</div>
<div id="imagewrapper">
    <div id="images">
<?php

$i=0;
$j=0;
$photoDesc = "";

$randomImages = getImageStatistic(getOption('wongm_randompage_count'), 'random');
while ($i < getOption('wongm_randompage_count'))
{
	echo '<tr class="trio">';

	while ($j < 3)
	{
		$randomImage = $randomImages[$i];
		$randomImageURL = $randomImage->getLink();
		$photoTitle = $randomImage->getTitle();
		$photoDate = strftime(getOption('date_format'), strtotime($randomImage->getDateTime()));
		$imageCode = "<img src='".$randomImage->getSizedImage(getOption('image_size'))."' alt='".$photoTitle."'>";

		$albumForPhoto = $randomImage->getAlbum();
		$photoAlbumTitle = $albumForPhoto->getTitle();
		$photoPath = $albumForPhoto->getLink();

		if ($photoDesc == '')
		{
			$photoDesc = $photoTitle;
		}
		else
		{
			$photoDesc = 'Description: '.$photoDesc;
		}
?>
<div class="image">
	<div class="imagethumb"><a href="<?php echo $randomImageURL; ?>">
		<?php echo $imageCode; ?>
	</a></div>
	<div class="imagetitle">	
		<h4><a href="<?php echo $randomImageURL?>"><?php echo $photoTitle; ?></a></h4>
		<small><?php echo $photoDate ?><?php if (zp_loggedin())
		{
			printRollingHitcounter($randomImage, true);
		} 
		?></small>
		<p>In Album: <a href="<?php echo $photoPath; ?>"><?php echo $photoAlbumTitle; ?></a></p>
	</div>
</div>
<?php
		$j++;
		$i++;
	}	//end while for cols
	$j=0;
}	//end while for rows
?>
</div>
</div>
<?php
include_once('footer.php');
?>