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
		echo "{x: Date.UTC(" . str_replace('-', ',', $key) . "), y:" . $value . ", url: '" . $archiveURL . "'}";
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
	<span id="breadcrumb"><span class="lede"><a href="<?php echo getGalleryIndexURL(); ?>" title="Gallery Index"><?php echo getGalleryTitle(); ?></a> &raquo;</span> 
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
include_once('footer.php'); 
?>
