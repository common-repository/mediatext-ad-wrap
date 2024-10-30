<?php
/*
Plugin Name: MediaText Ad Wrap
Plugin URI: http://justtalkaboutweb.com/
Description: Simple plugin to wrap MediaText zone tags around content and/or comments
Version: 1.0.0
Author: Jimmy Vu
Author URI: http://justtalkaboutweb.com/


Copyright 2008  Jimmy Vu  (email : jimmy [a t ] justtalkaboutweb DOT com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/
// set default values on install
function mediatext_ad_wrap_install() {
	if(get_option('mediatext_wrap_content')=="") {
    	update_option('mediatext_wrap_content', "Yes"); // wrap content by default
	}
	
	if(get_option('mediatext_exclude_comment')=="") { // exclude comment text by default
		update_option('mediatext_exclude_comment', "Yes");
	}
  
}

add_action("activate_mediatext_ad_wrap/mediatext_ad_wrap.php", "mediatext_ad_wrap_install");

//add MediaText menu to the wordpress options menu
function mediatext_add_options(){
  if(function_exists('add_options_page')){
    add_options_page('MediaText Ad Wrap', 'MediaText Ad Wrap', 9, basename(__FILE__), 'mediatext_options_subpanel');
  }
}

//validate options
switch($_POST['mediatext_action']){
	case 'Save':
	 	if($_POST['mediatext_wrap_content'] == "on") update_option('mediatext_wrap_content', "Yes");
	  	else update_option('mediatext_wrap_content', "No");
	  	if($_POST['mediatext_exclude_comment'] == "on") update_option('mediatext_exclude_comment', "Yes");
	  	else update_option('mediatext_exclude_comment', "No");
	  	break;
}

//option panel
function mediatext_options_subpanel(){
?>
<div class="wrap"> 
  	<h2>MediaText Ad Wrap Options</h2> 
  	<form name="form1" method="post">
		<fieldset class="options">
			<legend>Options (applicable to single post page only)</legend>
			<INPUT TYPE=CHECKBOX NAME="mediatext_wrap_content" <?php if(get_option('mediatext_wrap_content')=="Yes") echo "CHECKED=on"; ?>> Wrap Content<BR><BR>
			<INPUT TYPE=CHECKBOX NAME="mediatext_exclude_comment" <?php if(get_option('mediatext_exclude_comment')=="Yes") echo "CHECKED=on"; ?>> Exclude Comment Text<BR>
		</fieldset>
		<br />
		<input type="submit" name="mediatext_action" value="Save" />
	</form>
</div>

<?php
}


add_action('admin_menu', 'mediatext_add_options');

// main functionality
function mediatext_ad_include ($text)
{
	if(is_single()){
		return '<div id="mediatext">'.$text.'</div>';
	}
	
	return $text;
}

function mediatext_ad_exclude ($text)
{
	if(is_single()){
		return '<div class="NOMEDIATEXT">'.$text.'</div>';
	}
}


if(get_option('mediatext_wrap_content') == "Yes")
	add_filter ('the_content', 'mediatext_ad_include');

if(get_option('mediatext_exclude_comment') == "Yes")
	add_filter ('comment_text', 'mediatext_ad_exclude');

?>