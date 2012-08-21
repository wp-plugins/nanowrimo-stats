<?php
/*
Plugin Name: NaNoWriMo Stats
Plugin URI: http://plugins.camilstaps.nl/plugins/nanowrimo-stats/
Description: Allows you to show your NaNoWriMo Stats in posts, pages and sidebar widgets.
Version: 1.2.1
Author: Camil Staps
Author URI: http://camilstaps.nl
License: GPL2

Copyright 2012  Camil Staps  (email : info@camilstaps.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

error_reporting(E_ALL);

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'nanostats_install');

/* Runs on plugin deactivation*/
register_deactivation_hook(__FILE__, 'nanostats_remove' );

function nanostats_install() {
	add_option('nanostats_username','camilstaps','','yes');
	add_option('nanostats_region','europe-holland-belgium','','yes');
	add_option('nanostats_width','600','','yes');
	add_option('nanostats_height','350','','yes');
	add_option('nanostats_color_wordcount','#8888cc','','yes');
	add_option('nanostats_color_goal','#674732','','yes');
	add_option('nanostats_color_daily','#000000','','yes');
	add_option('nanostats_title','NaNoWriMo Stats for %u','','yes');
	
	file_get_contents('http://plugins.camilstaps.nl/wp-content/plugins/nanowrimo-stats-logger/logger.php?url='.urlencode($_SERVER['HTTP_HOST']).'&action=add');
}

function nanostats_remove() {
	delete_option('nanostats_username');
	delete_option('nanostats_region');
	delete_option('nanostats_width');
	delete_option('nanostats_height');
	delete_option('nanostats_color_wordcount');
	delete_option('nanostats_color_goal');
	delete_option('nanostats_color_daily');
	delete_option('nanostats_title');
	
	file_get_contents('http://plugins.camilstaps.nl/wp-content/plugins/nanowrimo-stats-logger/logger.php?url='.urlencode($_SERVER['HTTP_HOST']).'&action=remove');
}

/* ADMIN MENU */

if (is_admin()) {
	/* Call the html code */
	add_action('admin_menu', 'nanostats_admin_menu');

	function nanostats_admin_menu() {
		add_options_page('NaNoWriMo Stats Options', 'NaNoWriMo Stats', 'administrator','nanowrimo_stats', 'nanostats_option_page');
	}
	
	function nanostats_option_page() {
		include(dirname(__FILE__).'/admin.php');
	}
}

/* SHORTCODE */

function objectsIntoArray($arrObjData, $arrSkipIndices = array()) {
	$arrData = array();
   
	// if input is object, convert into array
	if (is_object($arrObjData)) {
		$arrObjData = get_object_vars($arrObjData);
	}
   
	if (is_array($arrObjData)) {
		foreach ($arrObjData as $index => $value) {
			if (is_object($value) || is_array($value)) {
				$value = objectsIntoArray($value, $arrSkipIndices);
			}
			if (in_array($index, $arrSkipIndices)) {
				continue;
			}
			$arrData[$index] = $value;
		}
	}
	return $arrData;
}

$nStats = 1;

function showNaNoStats($atts) {
	global $nStats;
	if (isset($atts['width'])) {
		$width = $atts['width'];
	} else {
		$width = get_option('nanostats_width');
	}
	if (isset($atts['height'])) {
		$height = $atts['height'];
	} else {
		$height = get_option('nanostats_height');
	}
	if (isset($atts['username'])) {
		$username = $atts['username'];
	} else {
		$username = get_option('nanostats_username');
	}
	if (isset($atts['wccolor'])) {
		$wccolor = $atts['wccolor'];
	} else {
		$wccolor = get_option('nanostats_color_wordcount');
	}
	if (isset($atts['goalcolor'])) {
		$goalcolor = $atts['goalcolor'];
	} else {
		$goalcolor = get_option('nanostats_color_goal');
	}
	if (isset($atts['dailycolor'])) {
		$dailycolor = $atts['dailycolor'];
	} else {
		$dailycolor = get_option('nanostats_color_daily');
	}
	if (isset($atts['showtitle'])) {
		$showtitle = $atts['showtitle'];
	} else {
		$showtitle = 'true';
	}
	if (isset($atts['showtotals'])) {
		$showtotals = $atts['showtotals'];
	} else {
		$showtotals = 'true';
	}
	if (isset($atts['showdaily'])) {
		$showdaily = $atts['showdaily'];
	} else {
		$showdaily = 'true';
	}
	if (isset($atts['showgoals'])) {
		$showgoals = $atts['showgoals'];
	} else {
		$showgoals = 'true';
	}
	
	if ($showtitle=='true') {
		$title = get_option('nanostats_title');
		$title = str_replace('%u',$username,$title);
	}
	
	$xmlUrl = "http://nanowrimo.org/wordcount_api/wchistory/".$username; 
	$xmlStr = file_get_contents($xmlUrl);
	$xmlObj = simplexml_load_string($xmlStr);
	$nanodata = objectsIntoArray($xmlObj);
	$data = $nanodata['wordcounts']['wcentry'];
	
	$wordcounts = '';
	$dailywc = '';
	$goals = '';
	$wordcount = 0;
	$year = substr($data[0]['wcdate'],0,4);
	$current = false;
	if (date('Y-m')==$year.'-11') $current = true;
	$entry = 0;
	for ($day=1;$day!=31;$day++) {
		# dailywc
		if ($day<10) {
			$dayFormat = '0'.$day;
		} else {
			$dayFormat = $day;
		}
		$dailywc .= '['.$day.',';
		if ($data[$entry]['wcdate'] == $year.'-11-'.$dayFormat) {
			$wordcount += $data[$entry]['wc'];
			$dailywc .= $data[$entry]['wc'];
			$entry++;
		} else {
			$dailywc .= 0;
		}
		$dailywc .= ']';
		if ($day!=30) $dailywc .= ',';
		
		# wordcount
		$wordcounts .= '['.$day.',';
		if (!$current || date('d')>$day) {
			$wordcounts .= $wordcount;
		} else {
			$wordcounts .= 0;
		}
		$wordcounts .= ']';
		if ($day!=30) $wordcounts .= ',';
		
		# goal
		$goal = round(50000/30*$day);
		$goals .= '['.$day.','.$goal.']';
		if ($day!=30) $goals .= ',';
	}
	
	$max = array();
	if ($showtotals!='false') $max[] = 10000*ceil($wordcount*1.1/10000);
	if ($showdaily!='false') $max[] = 10000*ceil($data[$entry-1]['wc']*1.1/10000);
	if ($showgoals!='false') $max[] = 10000*ceil($goal*1.1/10000);
	$max = max($max);
	if ($max>10000) $numberTicks = $max/10000 + 1;
	if ($max<=10000) $numberTicks = $max/1000 + 1;
	
	$barWidth = floor(($width-50)/30-8);
	
	$showlines = '';
	if ($showtotals!='false') $showlines .= ',totals';
	if ($showdaily!='false') $showlines .= ',daily';
	if ($showgoals!='false') $showlines .= ',goals';
	$showlines = '['.substr($showlines,1).']';
	
	$showcolors = '';
	if ($showtotals!='false') $showcolors .= ',"'.$wccolor.'"';
	if ($showdaily!='false') $showcolors .= ',"'.$dailycolor.'"';
	if ($showgoals!='false') $showcolors .= ',"'.$goalcolor.'"';
	$showcolors = '['.substr($showcolors,1).']';
	
	$showseriesoptions = '';
	if ($showtotals!='false') $showseriesoptions .= ',{
							renderer: $nanostatsJ.jqplot.BarRenderer,
							rendererOptions: {
								barWidth: '.$barWidth.',
								barPadding: -'.$barWidth.'
							},
							highlighter: { formatString: "Day: %s, total: %d" }
						}';
	if ($showdaily!='false') $showseriesoptions .= ',{
							renderer: $nanostatsJ.jqplot.BarRenderer,
							rendererOptions: {
								barWidth: '.$barWidth.',
								barPadding: -'.$barWidth.'
							},
							highlighter: { formatString: "Day: %s, words written: %d" }
						}';
	if ($showgoals!='false') $showseriesoptions .= ',{
							markerOptions: {
								size: 0
							},
							highlighter: { formatString: "Day: %s, goal: %d", showMarker: true }
						}';
	$showseriesoptions = '['.substr($showseriesoptions,1).']';
	
	$return = '
	<link class="include" rel="stylesheet" type="text/css" href="'.plugin_dir_url(__FILE__).'jquery.jqplot.min.css" />
    <script class="include" type="text/javascript" src="'.plugin_dir_url(__FILE__).'jquery.min.js"></script>

    <div id="nanostats-'.$nStats.'" style="width:'.$width.'px; height:'.$height.'px;"></div>
	<script class="code" type="text/javascript">
		$nanostatsJ(document).ready(function(){
			var daily = ['.$dailywc.'];
			var totals = ['.$wordcounts.'];
			var goals = ['.$goals.'];
			
			var plot1 = $nanostatsJ.jqplot(\'nanostats-'.$nStats.'\', '.$showlines.', {
				highlighter: {
					show: true,
					showMarker: false
				},
				seriesColors: '.$showcolors.',
				series:'.$showseriesoptions.',
				title: {
					text: "'.$title.'",
					show: '.$showtitle.'
				},
				axes: {
					xaxis: {
						renderer: $nanostatsJ.jqplot.CategoryAxisRenderer
					},
					yaxis: {
						min: 0,
						max: '.$max.',
						numberTicks: '.$numberTicks.',
						tickOptions: {formatString: \'%d\'}
					}
				}
			});
		});
	</script> 
    <script class="include" type="text/javascript" src="'.plugin_dir_url(__FILE__).'jquery.jqplot.min.js"></script>
	<script class="include" language="javascript" type="text/javascript" src="'.plugin_dir_url(__FILE__).'jqplot.barRenderer.min.js"></script>
    <script class="include" language="javascript" type="text/javascript" src="'.plugin_dir_url(__FILE__).'jqplot.categoryAxisRenderer.min.js"></script>
    <script class="include" language="javascript" type="text/javascript" src="'.plugin_dir_url(__FILE__).'jqplot.canvasAxisTickRenderer.min.js"></script>
    <script class="include" language="javascript" type="text/javascript" src="'.plugin_dir_url(__FILE__).'jqplot.canvasTextRenderer.min.js"></script>
    <script class="include" language="javascript" type="text/javascript" src="'.plugin_dir_url(__FILE__).'jqplot.highlighter.min.js"></script>';
	$nStats++;
	return $return;
}

function showNaNoWidget_participant($atts) {
	if (isset($atts['username'])) {
		$username = $atts['username'];
	} else {
		$username = get_option('nanostats_username');
	}
	if ($atts['showpercent']=='true') $extra .= ',pc';
	if ($atts['showdays']=='true') $extra .= ',days';
	if ($atts['showwords']!='true') $extra .= ',wc';
	if (isset($atts['goal'])) $extra .= ',goal='.$atts['goal'];
	if ($atts['show_username']=='true') {
		return '<img class="nanowidget" style="border:0;" src="http://www.nanowrimo.org/widget/LiveSupporter/'.$username.$extra.'.png" alt="NaNoWriMo Participant Widget"/>';
	} else {
		return '<img class="nanowidget" style="border:0;" src="http://www.nanowrimo.org/widget/LiveParticipant/'.$username.$extra.'.png" alt="NaNoWriMo Participant Widget"/>';
	}
}

function showNaNoWidget_calendar($atts) {
	if (isset($atts['username'])) {
		$username = $atts['username'];
	} else {
		$username = get_option('nanostats_username');
	}
	if ($atts['showpercent']=='true') $extra .= ',pc';
	if ($atts['showdays']=='true') $extra .= ',days';
	if ($atts['showwords']!='true') $extra .= ',wc';
	if (isset($atts['goal'])) $extra .= ',goal='.$atts['goal'];
	return '<img class="nanowidget" style="border:0;" src="http://www.nanowrimo.org/widget/MyMonth/'.$username.$extra.'.png" alt="NaNoWriMo Calendar Widget"/>';
}

function showNaNoWidget_progress($atts) {
	if (isset($atts['username'])) {
		$username = $atts['username'];
	} else {
		$username = get_option('nanostats_username');
	}
	if ($atts['showpercent']=='true') $extra .= ',pc';
	if ($atts['showdays']=='true') $extra .= ',days';
	if ($atts['showwords']!='true') $extra .= ',wc';
	if (isset($atts['goal'])) $extra .= ',goal='.$atts['goal'];
	return '<img class="nanowidget" style="border:0;" src="http://www.nanowrimo.org/widget/graph/'.$username.$extra.'.png" alt="NaNoWriMo Progress Widget"/>';
}

function showNaNoWidget_wordwar($atts) {
	$i = 1;
	while (isset($atts['username'.$i])) {
		$usernames .= ','.$atts['username'.$i];
		$i++;
	}
	if ($i<3) $usernames .= ','.get_option('nanostats_username');
	$usernames = substr($usernames,1);
	
	if ($atts['showpercent']=='true') $extra .= ',pc';
	if ($atts['showdays']=='true') $extra .= ',days';
	if ($atts['showwords']!='true') $extra .= ',wc';
	if (isset($atts['goal'])) $extra .= ',goal='.$atts['goal'];
	
	return '<img class="nanowidget" style="border:0;" src="http://www.nanowrimo.org/widget/WordWar/'.$usernames.$extra.'.png" alt="NaNoWriMo WordWar Widget"/>';
}

function showNaNoWidget_regionstatus($atts) {
	if (isset($atts['region'])) {
		$region = $atts['region'];
	} else {
		$region = get_option('nanostats_region');
	}
	
	if ($atts['showpercent']=='true') $extra .= ',pc';
	if ($atts['showdays']=='true') $extra .= ',days';
	if ($atts['showwords']!='true') $extra .= ',wc';
	if (isset($atts['goal'])) $extra .= ',goal='.$atts['goal'];
	
	return '<img class="nanowidget" style="border:0;" src="http://www.nanowrimo.org/widget/RegionStatus/'.$region.$extra.'.png" alt="NaNoWriMo RegionStatus Widget"/>';
}

function showNaNoWidget_regionwar($atts) {
	$i = 1;
	while (isset($atts['region'.$i])) {
		$regions .= ','.$atts['region'.$i];
		$i++;
	}
	if ($i<3) $regions .= ','.get_option('nanostats_region');
	$regions = substr($regions,1);
	
	if ($atts['showpercent']=='true') $extra .= ',pc';
	if ($atts['showdays']=='true') $extra .= ',days';
	if ($atts['showwords']!='true') $extra .= ',wc';
	if (isset($atts['goal'])) $extra .= ',goal='.$atts['goal'];
	
	return '<img class="nanowidget" style="border:0;" src="http://www.nanowrimo.org/widget/RegionWar/'.$regions.$extra.'.png" alt="NaNoWriMo RegionWar Widget"/>';
}

function showNaNoStats_wordcount($atts) {
	if (isset($atts['username'])) {
		$username = $atts['username'];
	} else {
		$username = get_option('nanostats_username');
	}
	
	$xmlUrl = "http://nanowrimo.org/wordcount_api/wc/".$username; 
	$xmlStr = file_get_contents($xmlUrl);
	$xmlObj = simplexml_load_string($xmlStr);
	$nanodata = objectsIntoArray($xmlObj);
	return $nanodata['user_wordcount'];
}

add_shortcode('nanostats','showNaNoStats');
add_shortcode('nanowidget_participant','showNaNoWidget_participant');
add_shortcode('nanowidget_calendar','showNaNoWidget_calendar');
add_shortcode('nanowidget_progress','showNaNoWidget_progress');
add_shortcode('nanowidget_wordwar','showNaNoWidget_wordwar');
add_shortcode('nanowidget_regionstatus','showNaNoWidget_regionstatus');
add_shortcode('nanowidget_regionwar','showNaNoWidget_regionwar');
add_shortcode('nanostats_wordcount','showNaNoStats_wordcount');
?>