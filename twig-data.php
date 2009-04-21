<?php
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

require_once("twitter.class.php");

$twig_defaults = array(
	 'twig_config_twitter_username' => '',
	 'twig_config_twitter_password' => '',
	 'twig_config_refresh_interval' => '30',
	 'twig_config_tweet_limit' => '5',
	 'twig_config_hide_replies' => '1',
	 'twig_config_prepend_username' => '1',
	 'twig_config_trim_dashes' => '0',
	 'twig_config_date_format' => 'M j, Y @ h:i A',
	 'twig_config_tweet_filter' => '',
	 'twig_config_show_template' => '<div><p id="{show_element_id}"><a href="{show_trigger}">Show more Tweets...</a></p><p id="{hide_element_id}" style="display: none;"><a href="{hide_trigger}">Hide expanded Tweets...</a></p></div>',
	 'twig_config_template' => '<hr /><p id="{unique_id}"><strong>Twitter Update:</strong> {text}<br /><em>Posted on {date} from {source}</em></p>');

$twig_valid_commands = array("verify_login", "restore_defaults");
if(isset($_GET["command"]) && in_array($_GET["command"], $twig_valid_commands)) eval($_GET["command"]."();");

function verify_login() {
	$t = new twitter();
	$t->username = $_POST["username"];
	$t->password = $_POST["password"];
	$res = $t->showUser($_POST["username"]);
	echo json_encode($res != null);
}

function restore_defaults() {
  global $twig_defaults;
  echo json_encode($twig_defaults);
}
?>