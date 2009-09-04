<?php
session_start();
/*
Plugin Name: Twig
Plugin URI: http://www.danhendricks.com/source-code/wordpress/plugin-twig-twitter-aggregator/
Description: Display your Twitter updates mixed with your WordPress posts, chronologically.
Version: 0.17.1
Author: Daniel M. Hendricks
Author URI: http://www.danhendricks.com
*/
/*
// Copyright (c) 2009 Daniel M. Hendricks
// http://www.danhendricks.com

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
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define("TWIG_VERSION", "0.17.1");
define("TWIG_CLIENT_NAME", "Twig");
define("TWIG_URL", "http://www.danhendricks.com/source-code/wordpress/plugin-twig-twitter-aggregator/");

require_once("twig-data.php");

// HOOKS
register_activation_hook(__FILE__,'twig_install');
add_action('init', 'twig_init');

/* INITIALIZATION */

function twig_init() {
	global $twig_last_post_time;

	add_action('admin_menu', 'twig_admin_menu');

	$q = new WP_Query('showposts=1&offset='.(get_settings('posts_per_page')-1));
	$twig_last_post_time = strtotime($q->post->post_date);

	wp_enqueue_script('jquery');
	add_action('template_redirect', 'twig_load_public_javascript');
	add_action('admin_print_scripts', 'twig_load_admin_javascript');
}

function twig_load_public_javascript() {
  wp_enqueue_script('twig_common', '/'.PLUGINDIR.'/'.plugin_basename(dirname(__FILE__)).'/js/twig.js');
}

function twig_load_admin_javascript() {
  echo '<link type="text/css" rel="stylesheet" href="' . '/'.PLUGINDIR.'/'.plugin_basename(dirname(__FILE__)).'/css/twig-settings.css" />';
  wp_enqueue_script('jquery_validate', '/'.PLUGINDIR.'/'.plugin_basename(dirname(__FILE__)).'/js/jquery.validate.pack.js');
  wp_enqueue_script('twig_javascript', '/'.PLUGINDIR.'/'.plugin_basename(dirname(__FILE__)).'/js/twig-settings.js');
}

function twig_admin_menu() {
	$plugin = plugin_basename(__FILE__);
	add_filter("plugin_action_links_$plugin", 'twig_plugin_action_links' );
	add_menu_page('Twig Settings', TWIG_CLIENT_NAME, 9, plugin_basename(dirname(__FILE__))."/twig-settings.php");
}

function twig_plugin_action_links( $links ) {
	$settings_link = "<a href='options-general.php?page=".plugin_basename(dirname(__FILE__))."/twig-settings.php'>" . __('Settings') . "</a>";
	array_unshift( $links, $settings_link );
	return $links;
}

function twig_install () {
   global $wpdb;

	// CREATE CACHE TABLE
	$table_name = $wpdb->prefix . "twig_cache";
	if($wpdb->get_var("show tables like '".$table_name."'") != $table_name) {
		$sql = "CREATE TABLE " . $table_name . " (
			id varchar(15) NOT NULL,
			msg varchar(255) NOT NULL,
			d_posted datetime NOT NULL,
			d_added datetime NOT NULL,
			source varchar(384) default NULL,
			in_reply_to_screen_name varchar(50) default NULL,
			UNIQUE KEY id (id));";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}

/* MAIN CODE */

function twig_display_tweets() {
	$twig_last_post_time = twig_get_last_post_time();

	$twig_expand_started = false;
	$tweet_limit = (twig_get_settings('twig_config_tweet_limit') ? twig_get_settings('twig_config_tweet_limit') : false);
	$start_date = get_the_time('U');
	$tweets = twig_get_tweets_from_cache($twig_last_post_time);
	$twig_count = 0;

	if(is_array($tweets)) {

		foreach($tweets as $msg) {
			$d_created = strtotime($msg->d_posted);
			
			if($d_created > $start_date && $d_created < $twig_last_post_time) {
				if(!(twig_get_settings('twig_config_hide_replies') == 1 && !empty($msg->in_reply_to_screen_name))) {
					$twig_filter = trim(twig_get_settings('twig_config_tweet_filter'));
					if(empty($twig_filter) || (!empty($twig_filter) && stripos($msg->msg, $twig_filter))) {
						if(empty($tweet_limit) || $twig_count < $tweet_limit) {
              				echo twig_merge_template($msg);
            			} else {
              				if(!$twig_expand_started) {
                				twig_display_expand_collapse($msg);
              				}
              				echo twig_merge_template($msg);
              				$twig_expand_started = true;
            			}
          			}
				}
  			
	  			$twig_count++;
			}
		}
		if($twig_expand_started) echo "</div>";
	}
}

function twig_get_last_post_time() {
	global $wpdb;
	$result = $wpdb->get_var("SELECT UNIX_TIMESTAMP(post_date) FROM ".$wpdb->prefix."posts WHERE post_status = 'publish' AND ID > ".get_the_ID()." ORDER BY post_date ASC LIMIT 1");
	if(!isset($result)) $result = strtotime("now");
	return $result;
}

function twig_get_settings($value) {
  global $twig_defaults;
	if (get_option($value) == null && false) {
			update_option($value,$defaults[$value]);
			return $defaults[$value];
	} else {
			return stripslashes(get_option($value));
	}
}

function twig_get_tweets_from_cache($max_blog_date,$limit=false) {
    global $wpdb;
		$since_date = null;
		$since_id = null;
		$data = null;

		$last_modified = get_cache_last_modified();
		if($last_modified) $since_date = $last_modified->d_added;
		if($last_modified) $since_id = $last_modified->id;
		$data = "";

  	if(empty($since_id) || strtotime($since_date) < strtotime("-".twig_get_settings('twig_config_refresh_interval')." minutes")) {
      $tc = new twitter();
      $tc->username = twig_get_settings('twig_config_twitter_username');
      $tc->password = twig_get_settings('twig_config_twitter_password');

			$data = $tc->userTimeline(false,20,$since_id);
			if(is_object($data)) {
        twig_save_cache($data, $since_id);
      } else {
        echo "Twig exception: Twitter request failed.";
      }
		}

		return twig_read_cache($end_date);
}

function twig_save_cache($data, $since_id) {
  global $wpdb;

	if(is_object($data) && sizeof($data) > 0) {
		foreach($data as $tweet) {
      try {
				$wpdb->query("
					INSERT INTO ".$wpdb->prefix."twig_cache"." VALUES (
					'".mysql_real_escape_string($tweet->id)."',
					'".mysql_real_escape_string($tweet->text)."',
					'".date('Y-m-d H:i:s', strtotime(mysql_real_escape_string($tweet->created_at)))."',
					NOW(),
					'".mysql_real_escape_string($tweet->source)."',
					'".mysql_real_escape_string($tweet->in_reply_to_screen_name)."')"
				);
      } catch (Exception $e) {
          echo 'Twig exception: ',  $e->getMessage(), "\n";
      }
		}
	} else {
		$wpdb->query("UPDATE ".$wpdb->prefix."twig_cache"." SET d_added = NOW() WHERE id = '".$since_id."'");
	}
}

function twig_read_cache($limit_date=false,$limit=false) {
  global $wpdb;
	$sql = "SELECT * FROM ".$wpdb->prefix."twig_cache"." WHERE d_posted > '".date('Y-m-d H:i:s', $limit_date)."' ORDER BY d_posted DESC";
	if($limit) $sql .= " LIMIT ".$limit;
	return $wpdb->get_results($sql, OBJECT);
}

function get_cache_last_modified() {
  global $wpdb;
  return $wpdb->get_row('SELECT id, d_added FROM '.$wpdb->prefix."twig_cache".' ORDER BY d_added DESC LIMIT 1');
}

/* HELPER FUNCTIONS */

function twig_merge_template($tweet) {
	$formatted = twig_get_settings('twig_config_template');
	$tweet_text = twig_strip_dashes($tweet->msg, twig_get_settings('twig_config_trim_dashes') == 1);
	$tweet_text = twig_add_username_link($tweet_text, twig_get_settings('twig_config_prepend_username') == 1);
  	$tweet_text = twig_format_links($tweet_text);
	$formatted = str_ireplace("{unique_css_id}", "twig_tweet_".$tweet->id, $formatted);
	$formatted = str_ireplace("{source}", $tweet->source, $formatted);
	$formatted = str_ireplace("{date}", date(twig_get_settings('twig_config_date_format'), strtotime($tweet->d_posted)), $formatted);
	$formatted = str_ireplace("{text}", $tweet_text, $formatted);
	$formatted = str_ireplace("{id}", $tweet->id, $formatted);
	return $formatted;
}

function twig_display_expand_collapse($msg) {
  $twig_show_hide_template = twig_get_settings('twig_config_show_template');
  $twig_show_hide_template = str_ireplace("{show_element_id}", "twig_show_button_".$msg->id, $twig_show_hide_template);
  $twig_show_hide_template = str_ireplace("{hide_element_id}", "twig_hide_button_".$msg->id, $twig_show_hide_template);
  $twig_show_hide_template = str_ireplace("{show_trigger}", "javascript:twig_show_more('".$msg->id."');", $twig_show_hide_template);
  $twig_show_hide_template = str_ireplace("{hide_trigger}", "javascript:twig_hide_more('".$msg->id."');", $twig_show_hide_template);
  echo $twig_show_hide_template;
  echo "<div id=\"twig_block_".$msg->id."\" style=\"display: none;\">";
}

function twig_strip_dashes($txt, $b_enabled=false) {
	if($b_enabled) {
		return trim(trim($txt), "- ");
	} else {
		return $txt;
	}
}

function twig_add_username_link($txt, $b_enabled=false) {
	if($b_enabled) {
		return "<a href=\"https://twitter.com/".twig_get_settings('twig_config_twitter_username')."\">".twig_get_settings('twig_config_twitter_username')."</a> ".$txt;
	} else {
		return $txt;
	}
}

function twig_format_replies($txt, $username=false) {
	if($username) {
		return str_replace("@".$username, '@<a href="https://twitter.com/'.$username.'" rel="nofollow" target="_blank">'.$username.'</a>', $txt);
	} else {
		return $txt;
	}
}

function twig_format_links($txt) {
  $txt = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1">\\1</a>', $txt);
  $txt = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2">\\2</a>', $txt);
  $txt = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})', '<a href="mailto:\\1">\\1</a>', $txt);
  return $txt;
}
?>
