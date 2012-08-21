<?php
	if (!is_admin()) die('no rights');
	
	register_setting('nanostats_basic','nanostats_username');
	register_setting('nanostats_basic','nanostats_region');
	register_setting('nanostats_basic','nanostats_width');
	register_setting('nanostats_basic','nanostats_height');
	register_setting('nanostats_basic','nanostats_color_wordcount');
	register_setting('nanostats_basic','nanostats_color_goal');
	register_setting('nanostats_basic','nanostats_color_daily');
	register_setting('nanostats_basic','nanostats_title');	
?>

<div class="wrap" style="margin-top: 0;">
	<style type="text/css">
		pre {
			display: inline;
		}
		ul {
			list-style-type: disc;
			padding-left: 20px;
		}
		td {
			vertical-align: middle;
			height: 30px;
		}
		td#help {
			vertical-align: top;
			height: 200px;
		}
		td#help span.ver_spacer {
			display: block;
		}
		.helpicon {
			cursor: pointer;
		}
		
		#tabs {
			width: 100%;
			border-color: #808080;
			border-style: solid;
			border-width: 0 0 1px 0;
			position: fixed;
			background-color: #fff;
			margin: 0;
			padding-top: 10px;
			height: 30px;
		}
		#tabs ul {
			list-style-type: none;
			margin: 0;
		}
		#tabs ul li {
			background-color: #dfdfdf;
			display: inline-block;
			padding: 0 10px;
			height: 30px;
			line-height: 30px;
			font-size: 16px;
			text-align: center;
			border-style: solid;
			border-width: 1px;
			border-radius: 10px 10px 0 0;
			border-color: #808080;
			margin-bottom: 0;
			margin-right: 5px;
		}
		#tabs ul li.selected {
			background-color: #808080;
		}
		#tabs ul li a {
			font-weight: bold;
			text-decoration: none;
			color: #21759b;
			width: 100%;
			display: block;
		}
		#tabs ul li.selected a {
			color: #fff;
			text-shadow: 0 -1px 0 #333333;
		}
		
		#main {
			padding-top: 45px;
		}
		
		#main table input {
			width: 200px;
		}
		
		#docs, #form, #support, #donate {
			display: none;
		}
		#docs.selected, #form.selected, #support.selected, #donate.selected {
			display: block;
		}
	</style>
	<script type="text/javascript">
		<?php 
		
		$fields = array('username','region','width','height','color_wordcount','color_goal','color_daily','title');
		foreach ($fields as $field) {
			echo "function help_show_".$field."() {
					document.getElementById('help_nanostats_".$field."').style.display = 'block';
				}
				function help_hide_".$field."() {
					document.getElementById('help_nanostats_".$field."').style.display = 'none';
				}";
		}
		?>
		
		pages = ['form','docs','support','donate'];
		function openPage(page) {
			for (var i in pages) {
				document.getElementById(pages[i]).setAttribute("class","");
				document.getElementById('li' + pages[i]).setAttribute("class","");
			}
			document.getElementById(page).setAttribute("class","selected");
			document.getElementById('li' + page).setAttribute("class","selected");
			scroll(0,0);
		}
	</script>
	<div id="tabs">
		<ul>
			<li id="liform" class="selected"><a href="javascript:openPage('form');">Settings</a></li>
			<li id="lidocs"><a href="javascript:openPage('docs');">Documentation</a></li>
			<li id="lisupport"><a href="javascript:openPage('support');">Support</a></li>
			<li id="lidonate"><a href="javascript:openPage('donate');">Donate</a></li>
		</ul>
	</div>
	<div id="main">
		<span style="color:#f00;font-size:15px;font-variant:small-caps;">Important notice! The Office of Letters and Light requested me to change the name of this plugin, so that it wouldn't contain 'nanowrimo'. 
This was only possible by making a <i>new</i> plugin. That means that this plugin won't update anymore. Please download the 'NaNo Stats' plugin from <a href="http://wordpress.org/extend/plugins/nano-stats/" target="_blank">here</a>.
<br/>Apologies for any caused inconvenience. CS.</span>
		<div id="form" class="selected">
			<form method="post" action="">
			<?php 
				settings_fields('nanostats_basic'); 
				if (isset($_POST['nanostats_username'])) update_option('nanostats_username', $_POST['nanostats_username']);
				if (isset($_POST['nanostats_region'])) update_option('nanostats_region', $_POST['nanostats_region']);
				if (isset($_POST['nanostats_width'])) update_option('nanostats_width', $_POST['nanostats_width']);
				if (isset($_POST['nanostats_height'])) update_option('nanostats_height', $_POST['nanostats_height']);
				if (isset($_POST['nanostats_color_wordcount'])) update_option('nanostats_color_wordcount', $_POST['nanostats_color_wordcount']);
				if (isset($_POST['nanostats_color_goal'])) update_option('nanostats_color_goal', $_POST['nanostats_color_goal']);
				if (isset($_POST['nanostats_color_daily'])) update_option('nanostats_color_daily', $_POST['nanostats_color_daily']);
				if (isset($_POST['nanostats_title'])) update_option('nanostats_title', $_POST['nanostats_title']);
				if (isset($_POST['nanostats_username'])) {
					?><div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div><?php
				}
			?>
			<h2>NaNoWriMo Stats Options</h2>
				<br/>
				<table style="width:100%;" cellspacing="0">
					<tr>
						<td style="width:170px;">Default username <img src="../wp-content/plugins/nanowrimo-stats/help.gif" class="helpicon" onmouseover="help_show_username()" onmouseout="help_hide_username()"/></td>
						<td style="width:200px;"><input type="text" name="nanostats_username" value="<?php echo get_option('nanostats_username'); ?>" /></td>
						<td id="help" rowspan="8">
							<span id="help_nanostats_username"><span class="ver_spacer" style="height:0;"></span>Unless otherwise specified, this username will be used to show the statistics. When in a WordWar widget less than 2 usernames are given, this one will be added.</span>
							<span id="help_nanostats_region"><span class="ver_spacer" style="height:30px;"></span>The default region. When in a RegionWar widget less than 2 regions are given, this one will be added.
								<br/>You can lookup your region by going to <a target="_blank" href="http://nanowrimo.org/en/regions">http://nanowrimo.org/en/regions</a>, click on your region and copy the region from the URL. For example, my region is <a target="_blank" href="http://nanowrimo.org/en/regions/europe-holland-belgium">http://nanowrimo.org/en/regions/europe-holland-belgium</a>, so my region is "europe-holland-belgium".</span>
							<span id="help_nanostats_width"><span class="ver_spacer" style="height:60px;"></span>In pixels.</span>
							<span id="help_nanostats_height"><span class="ver_spacer" style="height:90px;"></span>In pixels.</span>
							<span id="help_nanostats_color_wordcount"><span class="ver_spacer" style="height:120px;"></span>The default color for the wordcount bars. (default: #8888cc)</span>
							<span id="help_nanostats_color_goal"><span class="ver_spacer" style="height:150px;"></span>The default color for the goal line. (default: #674732)</span>
							<span id="help_nanostats_color_daily"><span class="ver_spacer" style="height:180px;"></span>The default color for daily words written bars. (default: #000000)</span>
							<span id="help_nanostats_title"><span class="ver_spacer" style="height:210px;"></span>The title for your graph. Use %u to display the username. Keep empty for no title.</span>
						</td>
					</tr>
					<tr>
						<td>Default region <img src="../wp-content/plugins/nanowrimo-stats/help.gif" class="helpicon" onmouseover="help_show_region()" onmouseout="help_hide_region()"/></td>
						<td><input type="text" name="nanostats_region" value="<?php echo get_option('nanostats_region'); ?>" /></td>
					</tr>
					<tr>
						<td>Default width <img src="../wp-content/plugins/nanowrimo-stats/help.gif" class="helpicon" onmouseover="help_show_width()" onmouseout="help_hide_width()"/></td>
						<td><input type="text" name="nanostats_width" value="<?php echo get_option('nanostats_width'); ?>" /></td>
					</tr>
					<tr>
						<td>Default height <img src="../wp-content/plugins/nanowrimo-stats/help.gif" class="helpicon" onmouseover="help_show_height()" onmouseout="help_hide_height()"/></td>
						<td><input type="text" name="nanostats_height" value="<?php echo get_option('nanostats_height'); ?>" /></td>
					</tr>
					<tr>
						<td>Default WC color <img src="../wp-content/plugins/nanowrimo-stats/help.gif" class="helpicon" onmouseover="help_show_color_wordcount()" onmouseout="help_hide_color_wordcount()"/></td>
						<td><input type="text" name="nanostats_color_wordcount" value="<?php echo get_option('nanostats_color_wordcount'); ?>" /></td>
					</tr>
					<tr>
						<td>Default goal color <img src="../wp-content/plugins/nanowrimo-stats/help.gif" class="helpicon" onmouseover="help_show_color_goal()" onmouseout="help_hide_color_goal()"/></td>
						<td><input type="text" name="nanostats_color_goal" value="<?php echo get_option('nanostats_color_goal'); ?>" /></td>
					</tr>
					<tr>
						<td>Default todays words color <img src="../wp-content/plugins/nanowrimo-stats/help.gif" class="helpicon" onmouseover="help_show_color_daily()" onmouseout="help_hide_color_daily()"/></td>
						<td><input type="text" name="nanostats_color_daily" value="<?php echo get_option('nanostats_color_daily'); ?>" /></td>
					</tr>
					<tr>
						<td>Title <img src="../wp-content/plugins/nanowrimo-stats/help.gif" class="helpicon" onmouseover="help_show_title()" onmouseout="help_hide_title()"/></td>
						<td><input type="text" name="nanostats_title" value="<?php echo get_option('nanostats_title'); ?>" /></td>
					</tr>
				</table>
				<?php 
					submit_button(); 
					
					echo '<script type="text/javascript">';
					foreach ($fields as $field) {
						echo "document.getElementById('help_nanostats_".$field."').style.display = 'none';";
					}
					echo '</script>';
				?>
			</form>
			<br/>
			To get help on this page, view: <a href="http://plugins.camilstaps.nl/plugins/nanowrimo-stats/the-settings-page" target="_blank">Documenation: The Settings Page</a>.
		</div>
		<div id="docs">
			<h2>Documentation</h2>
			<br/>
			You can either have a look at <a href="http://plugins.camilstaps.nl/plugins/nanowrimo-stats">this online documentation</a> with examples and other nice stuff, or lookup something in the summary below:
			<br/>
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
				<li><pre>dailycolor</pre>: the color for the daily words bars (don't forget the '#' !)</li>
				<li><pre>showtotals</pre>: set to false if you don't want to see the totals (default: true)</li>
				<li><pre>showgoal</pre>: set to false if you don't want to see the goal line (default: true)</li>
				<li><pre>showdaily</pre>: set to false if you don't want to see the daily words written bars (default: true)</li>
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
		</div>
		<div id="support">
			<h2>Support</h2>
			<br/>
			For support, please have a look at <a href="http://plugins.camilstaps.nl/support/" target="_blank">http://plugins.camilstaps.nl/support/</a>.
		</div>
		<div id="donate">
			<h2>Donate</h2>
			<br/>
			For the options to donate, see <a href="http://plugins.camilstaps.nl/donate/" target="_blank">http://plugins.camilstaps.nl/donate/</a>.
		</div>
	</div>
	<div style="width:25%;float:left;margin:0;padding:0;">
	</div>
</div>