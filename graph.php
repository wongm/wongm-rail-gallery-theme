<?php  $startTime = array_sum(explode(" ",microtime())); if (!defined('WEBPATH')) die(); 

if (isset($_GET['page']))
{
	//header('Content-Type: application/json');
	echo $_GET['callback']. "([";
	$lastValue = $i = 0;
	foreach (getAllDates() as $key => $value)
	{
		$archiveURL = SEO_WEBPATH . '/' . _ARCHIVE_ . '/?page=' . substr($key, 0, 7);
		
		if (isset($_GET['cumulative'])) {
			$value = $lastValue + $value;
		}
		
		if ($i > 0) {
			echo ",";
		}
		$bits = explode('-', $key);
		echo "{x: Date.UTC(" . $bits[0] . "," . ($bits[1] - 1) . "," . $bits[2] . "), y:" . $value . ", url: '" . $archiveURL . "'}";
		$lastValue = $value;
		$i++;
	}
	echo "])";
	die();
}

$title = "Photos taken per month";
$pageTitle = " - $title";
include_once('header.php');
?>
<div class="headbar">
	<span id="breadcrumb"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo; 
	<?php echo $title;?>
	</span><span id="righthead"><?php printSearchForm();?></span>
</div>
<div class="topbar">
	<h2><?php echo $title?></h2>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<div id="cumulative" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<div id="monthly" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script type="text/javascript">
$.getJSON('?page=data-<?php echo date('Ymd') ?>&cumulative=1&callback=?', function(data) { drawChart(data, 'cumulative', 'Total photos taken'); } );
$.getJSON('?page=data-<?php echo date('Ymd') ?>&callback=?', function(data) { drawChart(data, 'monthly', 'Photos taken per month'); } );

function drawChart(data, type, title) {
	Highcharts.chart(type, {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: title
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
        },
        xAxis: {
            type: 'datetime'
        },
        yAxis: {
			min: 0,
            title: {
                text: '# photos'
            }
        },
        legend: {
            enabled: false
        },
		tooltip: {
			style: {
				pointerEvents: 'auto'
			},
			useHTML: true,
			pointFormat: '<b>{point.y}</b> photos'
		},
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: '# photos',
            data: data,
			point: {
				events: {
					click: function() {
						window.open(this.url);
					}
				}
			}
        }]
    });
}
</script>
<?php
if (zp_loggedin())
{
	$locationSql = array();
	$locations = ['Geelong', 'Ascot Vale', 'William Street', 'Flagstaff', 'Boronia', 'Glenferrie', 'Sunshine', 'Blackburn', 'Route 57', 'Route 219', 'Route 903'];
	foreach ($locations as $location)
	{
		$locationSql[] = "SELECT YEAR(`date`) AS `year`, count(1) as `count`, '$location' AS `location` FROM ". prefix('images') . " WHERE `title` LIKE '%$location%' GROUP BY YEAR(`date`)";
	}
	
	$sql = join(" UNION ALL ", $locationSql);
	$results = query_full_array($sql);

	$yearLocationCounts = array();
	foreach($results as $locationYearCount)
	{
		$yearLocationCounts[$locationYearCount['year']][$locationYearCount['location']] = $locationYearCount['count'];
	}
?>
<div id="locations" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script type="text/javascript">
Highcharts.chart('locations', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Photos taken by location over time'
    },
    xAxis: {
        categories: [
            <?php echo join(",", array_keys($yearLocationCounts)); ?>
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: '# photos'
        }
    },
    tooltip: {
        headerFormat: '<span>{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} photos</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [
	<?php foreach ($locations as $location)
	{
		$locationCount = array();
		foreach (array_keys($yearLocationCounts) as $year)
		{
			$count = $yearLocationCounts[$year][$location];
			$locationCount[] = empty($count) ? 0 : $count;
		}
		?>
	{
		name: '<?php echo $location; ?>',
		data: [<?php echo join(",", $locationCount); ?>]
	},
	<?php } ?>
    ]
});
</script>
<?
}

include_once('footer.php'); 
?>
