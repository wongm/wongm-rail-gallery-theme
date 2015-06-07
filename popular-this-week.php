<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

// reset hitcounters
if ( zp_loggedin() ) {
	$time = time();
	$dateCurrent = date("Y-m-d");
	$dateLastWeek = date("Y-m-d", $time - (60*60*24*6.5));
	$dateLastMonth = date("Y-m-d", $time - (60*60*24*30));
	
	// ensure all new images have the reset date set to today
	$sqlToReset = "UPDATE " . prefix('images') . " SET hitcounter_month_reset = '$dateCurrent' WHERE hitcounter_month_reset IS NULL";
	query($sqlToReset);
	
	$sqlToReset = "UPDATE " . prefix('images') . " SET hitcounter_week_reset = '$dateCurrent' WHERE hitcounter_week_reset IS NULL";
	query($sqlToReset);
	
	// reset the week / month hitcounter if required
	$sqlToReset = "UPDATE " . prefix('images') . " SET hitcounter_month_reset = '$dateCurrent', hitcounter_month = 0 WHERE hitcounter_month_reset < '$dateLastMonth'";
	query($sqlToReset);
	
	$sqlToReset = "UPDATE " . prefix('images') . " SET hitcounter_week_reset = '$dateCurrent', hitcounter_week = 0 WHERE hitcounter_week_reset < '$dateLastWeek'";
	query($sqlToReset);
}

$key = 'this-week';
$popularImageText['key'] = $key;
setCustomPhotostream($popularImageText[$key]['where'], "", $popularImageText[$key]['order']);

require_once('uploads-base.php');
?>