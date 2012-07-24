<?php
/*
Plugin Name: NaNoWriMo Stats
Plugin URI: http://plugins.camilstaps.nl/nanowrimo-stats/
Description: Allows you to show your NaNoWriMo Stats in posts, pages and sidebar widgets.
Version: 1.0.3
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
	add_option('nanostats_title','NaNoWriMo Stats for %u','','yes');
	
	file_get_contents('http://plugins.camilstaps.nl/wp-content/plugins/nanowrimo-stats-logger/logger.php?url='.urlencode($_SERVER['SERVER_NAME']).'&action=add');
}

function nanostats_remove() {
	delete_option('nanostats_username');
	delete_option('nanostats_region');
	delete_option('nanostats_width');
	delete_option('nanostats_height');
	delete_option('nanostats_color_wordcount');
	delete_option('nanostats_color_goal');
	delete_option('nanostats_title');
	
	file_get_contents('http://plugins.camilstaps.nl/wp-content/plugins/nanowrimo-stats-logger/logger.php?url='.urlencode($_SERVER['SERVER_NAME']).'&action=remove');
}

/* ADMIN MENU */

if (is_admin()) {
	/* Call the html code */
	add_action('admin_menu', 'nanostats_admin_menu');

	function nanostats_admin_menu() {
		add_options_page('NaNoWriMo Stats Options', 'NaNoWriMo Stats', 'administrator','nanowrimo_stats', 'nanostats_option_page');
	}
	
	function nanostats_option_page() {
		register_setting('nanostats_basic','nanostats_username');
		register_setting('nanostats_basic','nanostats_region');
		register_setting('nanostats_basic','nanostats_width');
		register_setting('nanostats_basic','nanostats_height');
		register_setting('nanostats_basic','nanostats_color_wordcount');
		register_setting('nanostats_basic','nanostats_color_goal');
		register_setting('nanostats_basic','nanostats_title');
		?>
		<div class="wrap">
			<style type="text/css">
				pre {
					display: inline;
				}
				ul {
					list-style-type: disc;
					padding-left: 20px;
				}
			</style>
			<form method="post" action="">
			<?php 
				settings_fields('nanostats_basic'); 
				if (isset($_POST['nanostats_username'])) update_option('nanostats_username', $_POST['nanostats_username']);
				if (isset($_POST['nanostats_region'])) update_option('nanostats_region', $_POST['nanostats_region']);
				if (isset($_POST['nanostats_width'])) update_option('nanostats_width', $_POST['nanostats_width']);
				if (isset($_POST['nanostats_height'])) update_option('nanostats_height', $_POST['nanostats_height']);
				if (isset($_POST['nanostats_color_wordcount'])) update_option('nanostats_color_wordcount', $_POST['nanostats_color_wordcount']);
				if (isset($_POST['nanostats_color_goal'])) update_option('nanostats_color_goal', $_POST['nanostats_color_goal']);
				if (isset($_POST['nanostats_title'])) update_option('nanostats_title', $_POST['nanostats_title']);
				if (isset($_POST['nanostats_username'])) {
					?><div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div><?php
				}
			?>
			<h2>NaNoWriMo Stats Options</h2>
				Need any help? Please have a look at the <a href="http://plugins.camilstaps.nl/nanowrimo-stats">documentation</a>. There's a <a href="http://plugins.camilstaps.nl/support">Support page</a> too.<br/>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Default username</th>
						<td><input type="text" name="nanostats_username" value="<?php echo get_option('nanostats_username'); ?>" /></td>
						<td>Unless otherwise specified, this username will be used to show the statistics. When in a WordWar widget less than 2 usernames are given, this one will be added.</td>
					</tr>
					<tr valign="top">
						<th scope="row">Default region</th>
						<td><input type="text" name="nanostats_region" value="<?php echo get_option('nanostats_region'); ?>" /></td>
						<td>The default region. When in a RegionWar widget less than 2 regions are given, this one will be added.
							<br/>You can lookup your region by going to <a target="_blank" href="http://nanowrimo.org/en/regions">http://nanowrimo.org/en/regions</a>, click on your region and copy the region from the URL. For example, my region is <a target="_blank" href="http://nanowrimo.org/en/regions/europe-holland-belgium">http://nanowrimo.org/en/regions/europe-holland-belgium</a>, so my region is "europe-holland-belgium".</td>
					</tr>
					<tr valign="top">
						<th scope="row">Default width</th>
						<td><input type="text" name="nanostats_width" value="<?php echo get_option('nanostats_width'); ?>" /></td>
						<td>In pixels.</td>
					</tr>
					<tr valign="top">
						<th scope="row">Default height</th>
						<td><input type="text" name="nanostats_height" value="<?php echo get_option('nanostats_height'); ?>" /></td>
						<td>In pixels.</td>
					</tr>
					<tr valign="top">
						<th scope="row">Default WC color</th>
						<td><input type="text" name="nanostats_color_wordcount" value="<?php echo get_option('nanostats_color_wordcount'); ?>" /></td>
						<td>The default color for the wordcount bars. (default: #8888cc)</td>
					</tr>
					<tr valign="top">
						<th scope="row">Default goal color</th>
						<td><input type="text" name="nanostats_color_goal" value="<?php echo get_option('nanostats_color_goal'); ?>" /></td>
						<td>The default color for the goal line. (default: #674732)</td>
					</tr>
					<tr valign="top">
						<th scope="row">Title</th>
						<td><input type="text" name="nanostats_title" value="<?php echo get_option('nanostats_title'); ?>" /></td>
						<td>The title for your graph. Use %u to display the username. Keep empty for no username</td>
					</tr>
				</table>
				<?php submit_button(); ?>
				<h3>NaNoWriMo Stats</h3>
				Adding <pre>[nanostats]</pre> to a post, page or sidebar widget will include your NaNoWriMo stats! 
				<br/>You can also add variables to this shortcode, like this: <pre>[nanostats width=500]</pre> 
				<br/>You can use the following variables:
				<br/>
				<ul>
					<li><pre>username</pre>: the username to show the statistics for</li>
					<li><pre>width</pre>: the width of the graph in pixels</li>
					<li><pre>height</pre>: the height of the graph in pixels</li>
					<li><pre>wccolor</pre>: the color for the wordcount bars (don't forget the '#' !)</li>
					<li><pre>goalcolor</pre>: the color for the goal line (don't forget the '#' !)</li>
					<li><pre>showtitle</pre>: set to false if you don't want a title</li>
				</ul>
				All variables you don't define, will get the default value you inputted above.
				<br/>
				<h3>NaNoWidgets</h3>
				You can also easily add NaNoWriMo widgets to your site, using the following shortcodes:
				<br/>
				<ul>
					<li><pre>[nanowidget_participant]</pre>
						<br/>Additional variables:
						<ul>
							<li><pre>show_username</pre>: set to true if you want the widget to display your username</li>
						</ul>
						</li>
					<li><pre>[nanowidget_calendar]</pre></li>
					<li><pre>[nanowidget_progress]</pre></li>
					<li><pre>[nanowidget_wordwar]</pre> (you should give the usernames in variables like <pre>username1=example username2=example</pre>)</li>
					<li><pre>[nanowidget_regionstatus]</pre></li>
					<li><pre>[nanowidget_regionwar]</pre> (you should give the regions in variables like <pre>region1=example region2=example</pre>)</li>
				</ul>
				All widgets have the following variables:
				<br/>
				<ul>
					<li><pre>username</pre>: override the default username setting (only for participant, calendar, progress and wordwar)
						<br/><pre>region</pre>: override the default region setting (only for regionstatus and regionwar)</li>
					<li><pre>showpercent</pre>: set to true if you want to see a percentage of your goal</li>
					<li><pre>showdays</pre>: set to true if you want to see which day of the month it is</li>
					<li><pre>showwords</pre>: set to true if you want the widget to display your wordcount</li>
					<li><pre>goal</pre>: give it a number to override the standard 50.000 words goal</li>
				</ul>
				<br/>
				<span style="font-color:#f00;">One of the variables <pre>showpercent</pre>, <pre>showdays</pre>, <pre>showwords</pre> should be set to true in every widget in order for the widget to work.
				<br/>
				<h3>Plain-text NaNoWidgets</h3>
				In case you don't want images and stuff, but just some plain-text stats, use one of the following shortcodes:
				<br/>
				<ul>
					<li><pre>[nanostats_wordcount]</pre>: will be replaced by your wordcount.
						<br/>Variables:
						<br/>
						<ul>
							<li><pre>username</pre>: override the default username setting</li>
						</ul>
						</li>
				</ul>
			</form>
		</div>
		<?php
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
	if (isset($atts['showtitle'])) {
		$showtitle = $atts['showtitle'];
	} else {
		$showtitle = 'true';
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
	$goals = '';
	$wordcount = 0;
	$year = substr($data[0]['wcdate'],0,4);
	$current = false;
	if (date('Y-m')==$year.'-11') $current = true;
	$entry = 0;
	for ($day=1;$day!=31;$day++) {
		# wordcount
		if ($day<10) {
			$dayFormat = '0'.$day;
		} else {
			$dayFormat = $day;
		}
		if ($data[$entry]['wcdate'] == $year.'-11-'.$dayFormat) {
			$wordcount += $data[$entry]['wc'];
			$entry++;
		}
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
	
	$max = 10000*ceil($wordcount*1.1/10000);
	
	$barWidth = floor(($width-50)/30-8);
	
	$return = '
	<link class="include" rel="stylesheet" type="text/css" href="'.plugin_dir_url(__FILE__).'jquery.jqplot.min.css" />
    <script class="include" type="text/javascript" src="'.plugin_dir_url(__FILE__).'jquery.min.js"></script>

    <div id="nanostats-'.$nStats.'" style="width:'.$width.'px; height:'.$height.'px;"></div>
	<script class="code" type="text/javascript">
		$nanostatsJ(document).ready(function(){
			var s1 = ['.$wordcounts.'];
			var s2 = ['.$goals.'];
			
			var plot1 = $nanostatsJ.jqplot(\'nanostats-'.$nStats.'\', [s1,s2], {
				seriesColors: ["'.$wccolor.'","'.$goalcolor.'"],
				series:[
						{
							renderer: $nanostatsJ.jqplot.BarRenderer,
							rendererOptions: {
								barWidth: '.$barWidth.',
								barMargin: 5
							},
							highlighter: {
								formatString: "Day %s: wordcount: %d"
							}
						},
						{
							highlighter: {
								formatString: "Day %s: goal: %d"
							},
							markerOptions: {
								size: 0
							}
						}
					],
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
						numberTicks: '.($max/10000+1).',
						tickOptions: {formatString: \'%d\'}
					}
				},
				highlighter: {
					show: true,
					sizeAdjust: 0,
					showMarker: false
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
		$usernames .= ','.$atts['username'+$i];
		$i++;
	}
	if ($i<2) $usernames .= ','.get_option('nanostats_username');
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
		$regions .= ','.$atts['region'+$i];
		$i++;
	}
	if ($i<2) $regions .= ','.get_option('nanostats_region');
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