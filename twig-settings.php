<?php
	if(isset($_POST['twig_admin_options'])):
		twig_update_settings();
?>
	<div id="message" class="updated fade"><p><strong>Settings successfully updated!</strong></p></div>
<?php endif; ?>
<div class="wrap"><h2>Twig Settings</h2>

<form action="options-general.php?page=<?php echo plugin_basename(dirname(__FILE__)); ?>/twig-settings.php" method="post" id="twig_admin_options" onsubmit="return twig_validateFields(this);">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <label for="separator">Twitter Username:</label>
            </th>
            <td>
                <input type="text" name="twig_config_twitter_username" id="twig_config_twitter_username" value="<?php echo twig_get_settings('twig_config_twitter_username'); ?>" size="32" class="required" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="separator">Twitter Password:</label>
            </th>
            <td>
                <input type="text" name="twig_config_twitter_password" id="twig_config_twitter_password" value="<?php echo twig_get_settings('twig_config_twitter_password'); ?>" size="32" class="required" />
                <input type="button" class="button-primary" name="twig_twitter_test" value="Test" onclick="checkLogin();" />
            </td>
        </tr>
	</table>
    
	<h2>Preferences</h2>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <label for="separator">Refresh Interval (minutes):</label>
            </th>
            <td>
                <input type="text" name="twig_config_refresh_interval" id="twig_config_refresh_interval" value="<?php echo twig_get_settings('twig_config_refresh_interval'); ?>" size="4" class="required" /><br />
        				<span class="setting-description">Time between each cache update from Twitter.<br />Note: Twitter only allows <a href="http://apiwiki.twitter.com/REST+API+Documentation#RateLimiting" target="_blank">100 requests per hour</a>, so configure this setting accordingly.</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="separator">Tweet limit between posts:</label>
            </th>
            <td>
                <input type="text" name="twig_config_tweet_limit" id="twig_config_tweet_limit" value="<?php echo twig_get_settings('twig_config_tweet_limit'); ?>" size="4" class="required" /><br />
        				<span class="setting-description">0 = no limit.  Useful if you Tweet a lot more than you post and want to reduce clutter.</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="separator">Date Format:</label>
            </th>
            <td>
                <input type="text" name="twig_config_date_format" id="twig_config_date_format" value="<?php echo twig_get_settings('twig_config_date_format'); ?>" size="15" /> (<a href="http://us3.php.net/date" target="_blank">Formatting Reference</a>)
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="separator">Only include Tweets that contain text:</label>
            </th>
            <td>
                <input type="text" name="twig_config_tweet_filter" id="twig_config_tweet_filter" value="<?php echo twig_get_settings('twig_config_tweet_filter'); ?>" size="15" /> (Leave blank to show all Tweets)<br />
        				<span class="setting-description">Example: Specifying "#wp" will only show Tweets that contain the "#wp" hashtag.</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="separator">Hide @replies?</label>
            </th>
            <td>
                <input type="checkbox" name="twig_config_hide_replies" id="twig_config_hide_replies" value="1" <?php echo (twig_get_settings('twig_config_hide_replies') ? "checked" : ""); ?> />
				<span class="setting-description">Would you like to hide @replies?</span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="separator">Prepend username to tweets?:</label>
            </th>
            <td>
                <input type="checkbox" name="twig_config_prepend_username" id="twig_config_prepend_username" value="1" <?php echo (twig_get_settings('twig_config_prepend_username') ? "checked" : ""); ?> />
				<span class="setting-description">Example: {twitter_username} is taking a nap.</span>
            </td>

        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="separator">Trim hyphens from tweet?</label>
            </th>
            <td>
                <input type="checkbox" name="twig_config_trim_dashes" id="twig_config_trim_dashes" value="1" <?php echo (twig_get_settings('twig_config_trim_dashes') ? "checked" : ""); ?> />
				<span class="setting-description">Example: A tweet that says, "- I'm taking a nap" would become "I'm taking a nap".</span>
            </td>

        </tr>
	</table>

	<h2>Templates</h2>

    <p><strong>Tweet Template:</strong><br /><textarea name="twig_config_template" id="twig_config_template" rows="7" wrap="off" style="width: 100%;"><?php echo twig_get_settings('twig_config_template'); ?></textarea><br />
		<span class="setting-description"><strong>Valid tags:</strong> (id}, {text}, {date}, {source}, {delete_button}, {unique_css_id}</span>
    </p>
    
    <p><strong>Show More/Hide Template (used if <tt>Tweet Limit</tt> is set):</strong><br /><textarea name="twig_config_show_template" id="twig_config_show_template" rows="4" wrap="off" style="width: 100%;"><?php echo twig_get_settings('twig_config_show_template'); ?></textarea><br />
		<span class="setting-description"><strong>Valid tags:</strong> {show_element_id}, {hide_element_id}, {show_trigger}, {hide_trigger}</span>
    </p>

	<h2>Twitter Cache</h2>
	<p><input type="checkbox" name="twig_config_refresh" id="twig_config_refresh" value="1" /> Clear cache (may be useful if you are experience data issues.)</p>

	<p class="submit">
    	<input type="submit" class="button-primary" name="twig_admin_options" value="Save Changes" />
        <input type="button" class="button-primary" name="twig_reset_options" value="Reset Defaults" onclick="restoreDefaults();" />
    </p></form>


    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="float: left;">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="4753129">
        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    <p>If you find this plugin useful, please consider donating!</p>
    <div style="clear: left;"></div>

<?php
function twig_update_settings() {
	global $wpdb;
	$update_arr = array(
		'twig_config_twitter_username'	=> $_POST["twig_config_twitter_username"],
		'twig_config_twitter_password'	=> $_POST["twig_config_twitter_password"],
		'twig_config_refresh_interval'	=> $_POST["twig_config_refresh_interval"],
		'twig_config_tweet_limit'		=> $_POST["twig_config_tweet_limit"],
		'twig_config_hide_replies'		=> (isset($_POST["twig_config_hide_replies"]) ? $_POST["twig_config_hide_replies"] : '0'),
		'twig_config_prepend_username'	=> (isset($_POST["twig_config_prepend_username"]) ? $_POST["twig_config_prepend_username"] : '0'),
		'twig_config_trim_dashes'		=>(isset($_POST["twig_config_trim_dashes"]) ? $_POST["twig_config_trim_dashes"] : '0'),
		'twig_config_show_template'	=> $_POST["twig_config_show_template"],
		'twig_config_date_format'		=> $_POST["twig_config_date_format"],
		'twig_config_tweet_filter'  => $_POST["twig_config_tweet_filter"],
		'twig_config_template'			=> $_POST["twig_config_template"]
	);
	
	foreach($update_arr as $key => $val) {
		update_option($key, $val);
	}

	/* REFRESH CACHE */
	if(isset($_POST['twig_config_refresh'])) {
		$wpdb->query("DELETE FROM `wp_twig_cache` ORDER BY d_posted DESC LIMIT 200");
	}
}
?>
