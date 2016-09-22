<?php $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

if (isset($_GET['token']))
{
	if ($_GET['token'] == getOption('wongm_cron_security_key'))
	{
		if (function_exists('updateHitcounterDates'))
		{
			updateHitcounterDates();
			echo "DONE";
		}
		else
		{
			echo "OK";
		}
		exit;
	}
}

header('HTTP/1.0 403 Forbidden');
exit;
?>