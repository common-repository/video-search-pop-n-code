<?php

/*
Plugin Name: Video Search Pop N Code
Plugin URI: http://honewatson.com/og/wordpress-video-plugin-search-pop-n-code/
Description: Plugin with Google Ajax Search for Videos plus Code and Placement
Date: 2008, April, 26
Author: Hone Watson
Author URI: http://honewatson.com
Version: 0.1
*/

/*
Author: Hone Watson
Website: http://honewatson.com
Copyright 2008 Hone Watson All Rights Reserved.


This software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/

function style_make() {


$gapikey = get_option(videosearchpopncode_api);
$dirfilename = explode('/wp-content/',dirname( __FILE__ ));
$dirfilename = '/wp-content/' . $dirfilename[1];
$jsfile = get_option(home) . $dirfilename . '/video.js';
echo '
<script src="http://www.google.com/uds/api?file=uds.js&v=1.0&source=uds-vsw&key='.$gapikey.'"
  type="text/javascript"></script>
<style type="text/css">
  @import url("http://www.google.com/uds/css/gsearch.css");
</style>


<script type="text/javascript">
window._uds_vsw_donotrepair = true;
</script>
<script src="'.$jsfile.'?mode=new"
type="text/javascript"></script>
<style type="text/css">
@import url("http://www.google.com/uds/solutions/videosearch/gsvideosearch.css");

.gsc-control {width:200px;}
#videoControl{width:200px;}
video-result-table_gsvsc video-result-table_gsvsc-1, .searchForm_gsvsc table.gsc-search-box, .searchForm_gsvsc table.gsc-branding, .tiny-results_gsvsc table.video-result-table_gsvsc {width:90%;}
.playerBox_gsvsc .player_gsvsc {width : 480px; height : 380px;border:5px solid #fff; }
.playerBox_gsvsc, .more_gsvsc {  position : absolute; padding:20px; background:#f4f4f4;
opacity : 0.90;
-moz-opacity : 0.90;
filter:alpha(opacity=90);
z-index : 9998; top:380px; left:150px;border:1px solid #333;}
.more_gsvsc, div.more_gsvsc:hover {top:340px; font-size:1.6em; padding: 10px 200px 10px 30px; border:0; background:#000; color:#fff; font-weight:bold; }
div.more_gsvsc:hover {text-decoration:none; color:#333;}
#video-search-wrap {margin:10px auto; text-align:left;width:200px;}
.linkage {margin:10px auto; padding:10px; background:#ccc; color:#000; font-size:0.9em; border:3px solid #333;}
.results_gsvsc div.video-result_gsvsc, .tiny-results_gsvsc div.tiny-video-result_gsvsc {width:136px; height:103px; border-color:#ccc; padding:2px;}
</style>

<script type="text/javascript">
  function LoadVideoSearchControl() {
    var options = {
      
    };
    var videoSearch = new GSvideoSearchControl(
                            document.getElementById("videoControl"),
                            [{ query : ""}], null, null, options);
  }
  
  // arrange for this function to be called during body.onload
  // event processing
  GSearch.setOnLoadCallback(LoadVideoSearchControl);
</script>


<style type="text/css">.side-info ul li#easyvideosearch{list-style:none; padding-left:0;margin-left:-18px;}#poststuff #excerptdiv .inside {color:#777777; margin-left:0;}#poststuff #excerptwrap {padding: 2px 3px;border-width: 1px;border-style: solid;}#excerptdiv {margin: 10px 8px 0 0;padding: 0;  	margin-left: 20px; 	margin-bottom: 20px; 	margin-right: 8px; } #excerptdiv #excerpt { height:2em;	border: 0; 	padding: 3px; 	font-size: 1.2em; font-weight:bold;	width: 100%; 	outline: none;}#infostory {padding:10px;} #infostory dt {font-weight:bold; font-size:1.2em; margin-top:10px; }#infostory dd {margin:5px 0; padding:0;}.searchForm_gsvsc input.gsc-input {width:90%;}</style>';
    
}

function searchado() {

echo '
<li id="easyvideosearch">
  <div id="video-search-wrap">
  <h3>Video Search</h3>
  <div id="videoControl">
    <span style="color:#676767;font-size:11px;margin:10px;padding:4px;">Loading...</span>
  </div>
  </div>
</li>
';

}


function poptube_add_admin()
{
	add_options_page('VideoSearchPopNCode', 'VideoSearchPopNCode', 8, 'videosearchpopncode', 'poptube_options');
}


// 0.8235 = the magic YouTube video ratio! H = (W * 0.8235)
$poptube_sizes = array(
						1 =>array(
							"name"	=>"Default - 425 x 355",
							"w"		=>"425",
							"h"		=>"355"
						),
						2 =>array(
							"name"	=>"Large - 700 x 576",
							"w"=>"700",
							"h"=>"576"
						),
						3 =>array(
							"name"	=>"Medium - 350 x 288",
							"w"=>"700",
							"h"=>"576"
						),
						4 =>array(
							"name"	=>"Small - 250 x 206",
							"w"=>"250",
							"h"=>"206"
						)
					);
					
$poptube_colors = array(
					1=>array(
						"a"=>"666666",
						"b"=>"d3d3d3"
					),
					2=>array(
						"a"=>"3A3A3A",
						"b"=>"999999"
					),
					3=>array(
						"a"=>"2B405B",
						"b"=>"6B8AB6"
					),
					4=>array(
						"a"=>"006699",
						"b"=>"54ABD6"
					),
					5=>array(
						"a"=>"234900",
						"b"=>"4E9400"
					),
					6=>array(
						"a"=>"E1600F",
						"b"=>"FEBD01"
					),
					7=>array(
						"a"=>"CC2550",
						"b"=>"E87A9F"
					),
					8=>array(
						"a"=>"402061",
						"b"=>"9461CA"
					),
					9=>array(
						"a"=>"5D1719",
						"b"=>"CD311B"
					)
				);


/*
 * The YouTube code 
 */
function poptube_content($content) {
	global $poptube_sizes, $poptube_colors;
	$global = intval(get_option('videosearchpopncode_global'));
	$size 	= intval(get_option('videosearchpopncode_size'));
	$border = intval(get_option('videosearchpopncode_border'));
	$rel	= intval(get_option('videosearchpopncode_rel'));
	$auto	= intval(get_option('videosearchpopncode_auto'));
	$color	= intval(get_option('videosearchpopncode_color'));
	
	
    $regex = '/\[youtube:(.*?)]/i';
	preg_match_all( $regex, $content, $matches );
	for($x=0; $x<count($matches[0]); $x++)
	{
		$parts = explode(" ", $matches[1][$x]);
		$vid= explode('/v/',$parts[0],2);
		$videocode = $vid[1];
		if(is_feed()){
			$replace = "<a href=\"http://www.youtube.com/watch?v=$vid\"><img src=\"http://img.youtube.com/vi/$vid/default.jpg\" width=\"130\" height=\"97\" border=0></a>";
		} else {
			if($global) {
				$replace = '<div class="prev"><div class="videohits"><object type="application/x-shockwave-flash" style="width:'.$poptube_sizes[$size]['w'].'px; height:'.$poptube_sizes[$size]['w'].'px;" data="http://www.youtube.com/v/'.$videocode.'"><param name="movie" value="http://www.youtube.com/v/'.$videocode.'&autoplay='.$auto.'&color1='.$poptube_colors[$color]['a'].'&color2='.$poptube_colors[$color]['b'].'&rel='.$rel.'&border='.$border.'" /></object></div></div>';
			} else {
				if(count($parts) > 1) {
					$width = $parts[1];
					
					if(count($parts) > 2){
						$height = $parts[2];
					} else {
						$height = ($width * 0.8235);
					}
					$replace = '<div class="prev"><div class="videohits"><object type="application/x-shockwave-flash" style="width:425px; height:373px;" data="http://www.youtube.com/v/'.$videocode.'">
<param name="movie" value="http://www.youtube.com/v/'.$videocode.'" /></object></div></div>';
				} else {
					$vid= explode('=',$matches[1][$x]);
					$vid = $vid[1];
					$replace = '<div class="prev"><div class="videohits"><object type="application/x-shockwave-flash" style="width:425px; height:373px;" data="http://www.youtube.com/v/'.$videocode.'">
<param name="movie" value="http://www.youtube.com/v/'.$videocode.'" /></object></div></div>';
				}
			}
		}
		$content = str_replace($matches[0][$x], $replace, $content);
	}
	return $content;
}


/*
 * Google Video Code
 * Settings in options do not work for these... well maybe height will...
 */
function googlevideo_content($content)
{
    $regex = '/\[googlevideo:(.*?)]/i';
	preg_match_all( $regex, $content, $matches );
	for($x=0; $x<count($matches[0]); $x++){
		$parts = explode(" ", $matches[1][$x]);
		if(count($parts) > 1){
			$vid= explode('=',$parts[0]);
			$googcode = $vid[1];
			$width = $parts[1];
			
			if(count($parts) > 2){
				$height = $parts[2];
			}
			else {
				$height = "";
			}
			
			$replace = '<div class="prev"><div class="videohits"><object type="application/x-shockwave-flash" style="width:425px; height:373px;" data="http://video.google.com/googleplayer.swf?docId='.$googcode.'">
<param name="movie" value="http://video.google.com/googleplayer.swf?docId='.$googcode.'" /></object></div></div>';
		} else {
			$vid= explode('=',$matches[1][$x]);
			$googcode = $vid[1];
			$replace = '<div class="prev"><div class="videohits"><object type="application/x-shockwave-flash" style="width:425px; height:373px;" data="http://video.google.com/googleplayer.swf?docId='.$googcode.'">
<param name="movie" value="http://video.google.com/googleplayer.swf?docId='.$googcode.'" /></object></div></div>';
		}
		$content = str_replace($matches[0][$x], $replace, $content);
	}
	return $content;
}

/*
 * The Options page for VideoSearchPopNCode. We rock... thanks.
 */
function poptube_options()
{	
	global $poptube_sizes,$poptube_colors;
	
	$options = array("videosearchpopncode_global","videosearchpopncode_border","videosearchpopncode_rel","videosearchpopncode_auto");
	
	if($_POST['action'] == 'save')
	{
		update_option('videosearchpopncode_size', $_POST['videosearchpopncode_size']);
		update_option('videosearchpopncode_color', $_POST['videosearchpopncode_color']);
		update_option('videosearchpopncode_api', $_POST['videosearchpopncode_api']);
		foreach($options as $o)
		{	
			
			$val = !empty($_POST[$o]);
			update_option($o, $val);
		}
	}
	
	$global = get_option('videosearchpopncode_global');
	$size 	= get_option('videosearchpopncode_size');
	$border = get_option('videosearchpopncode_border');
	$rel	= get_option('videosearchpopncode_rel');
	$auto	= get_option('videosearchpopncode_auto');
	$color	= get_option('videosearchpopncode_color');
	$apikeyg = get_option('videosearchpopncode_api');
	
?>
<!-- VideoSearchPopNCode - its the way forward -->
 <div class="wrap">
	<h2>Video Search Pop N Code Options</h2>


	
	<form name="form2" action="?page=videosearchpopncode" method="POST">
	<input type="hidden" name="action" value="save"/>
	<p class="submit"><input name="Submit" value="Update Options &raquo;" type="submit"></p>
	<table class="optiontable">
			<tr>
				<th scope="row">
					<b>Your Google Search API Key</b>
				</th>
				<td>
					<input type="text" name="videosearchpopncode_api" value="<?php echo $apikeyg;?>" />
				</td>
			</tr>
  
		</table>
        <p>When you sign up for an API key make sure you get a key for http://yourdomain.com without www so your key will work for the all subdomains of your site including www.  Sign up for an <a href="http://code.google.com/apis/ajaxsearch/signup.html" target="_blank"> API Key Here</a></p>
			<?if(!$global){ ?>
	<div style="border: 1px solid red; padding: 5px; clear:both; display:block; margin-top:20px; margin-bottom:20px; ">
	<b style="color: #DF3771">Warning!</b><br />The following options will not take effect if the global checkbox is not checked.
	</div>
	<?} ?>
	<table>
			<tr>
				<th scope="row">
					
					<b>Enable Global Options</b>
				</th>
				<td>
					<input type="checkbox" name="videosearchpopncode_global" <?if($global){echo"checked=\"yes\"";}?> value="1" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					Video Dimension
				</th>
				<td>
					<select name="videosearchpopncode_size">
					<?foreach($poptube_sizes as $key=>$s){ ?>
						<option value="<?=$key ?>" <?if($key == $size){ echo "selected=\"selected\"";} ?>><?=$s['name']; ?></option>
					<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					Enable Relevant Videos
				</th>
				<td>
					<input type="checkbox" name="videosearchpopncode_rel" <?if($rel){echo"checked=\"yes\"";}?> value="1" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					Autoplay Videos
				</th>
				<td>
					<input type="checkbox" name="videosearchpopncode_auto" <?if($auto){echo"checked=\"yes\"";}?> value="1" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					Enable Embed Border
				</th>
				<td>
					<input type="checkbox" name="videosearchpopncode_border" <?if($border){echo"checked=\"yes\"";}?> value="1" />
				</td>
			</tr>	
			<tr>
				<th scope="row">
					Border Style:
				</th>
				<td>
					
				</td>
			</tr>		
			<? foreach($poptube_colors as $key=>$c){?>
			<tr>
				<th scope="row">
				
				</th>
				<td>
				<INPUT TYPE=RADIO NAME="videosearchpopncode_color" VALUE="<?=$key ?>" style="float: left;" <?if($color == $key) echo "CHECKED"; ?>><div style="float: left;border: 1px solid #<?=$c['a']; ?>;height: 20px; width: 40px;"><div style="float:left;width: 20px; height: 20px; background-color: #<?=$c['a'] ?>;"></div><div style="float:left;width: 20px; height: 20px; background-color: #<?=$c['b'] ?>;"></div></div>
				</td>
			</tr>
			<?} ?>
		</table>
		<p class="submit"><input name="Submit" value="Update Options &raquo;" type="submit"></p>
	</form>
</div>
<?	
}

/*
 * Install VideoSearchPopNCode options. We like options, they give us variety in life.
 */
function poptube_install()
{ 
	add_option('videosearchpopncode_global', 	0, "Use global settings for videosearchpopncode");
	add_option('videosearchpopncode_size', 	1, "Defines video size");
	add_option('videosearchpopncode_color', 	1, "Defines border color");
	add_option('videosearchpopncode_border',	0, "Defines if we use a border");
	add_option('videosearchpopncode_rel',		0, "Show relevant videos");
	add_option('videosearchpopncode_auto',		0, "Autoplay videos");
	add_option('videosearchpopncode_api',		'', "");
}


add_filter('the_content','poptube_content');
add_filter('the_content','googlevideo_content');
add_filter('the_excerpt','poptube_content');
add_filter('the_excerpt','googlevideo_content');
add_action('admin_menu', 'poptube_add_admin');
add_action('post_relatedlinks_list', 'searchado');
add_action( 'admin_head', 'style_make' );

register_activation_hook(__FILE__,"poptube_install");

?>
