=== Plugin Name ===
Contributors: hendridm
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4753129
Tags: twitter, tweet, integrate, aggregator, cache, hashtag, api 
Requires at least: 2.7
Tested up to: 2.7.1
Stable tag: 0.11

Twig allows you to display your Twitter "tweets" intermingled among your WordPress posts, sorted chronologically.

== Description ==

REQUIRES PHP 5.2 OR HIGHER

Twig allows you to display your Tweets intermingled among your WordPress posts, sorted chronologically. Other plugins are available to display your tweets in a widget or separate list, however, this plugin displays your tweets on your front page as a part of your blog. It also caches your Twitter feed and updates at an interval specific my you.

Please note that this plugin is in the beta stage. If you encounter bugs or have feature requests, please feel free to let me know.

== Installation ==

1. Unzip the contents of the ZIP file and upload the 'twig' folder to your WordPress plugins folder.
2. Enable Twig in your WordPress plugins page.
3. Click the Settings link for Twig in the plugins page to configure Twig for the first time. At minimum, you'll need to enter your Twitter username and password (don't forget to click Save Changes!).
4. Add the following code to "the loop" in your theme's index.php file, near the top of the loop (ie, just under "while (have_posts())"):
&lt;?php if(is_home()) twig_display_tweets(); ?&gt;

== Frequently Asked Questions ==

= I'm getting an error.  What should I do? =

If you're getting an error, I can try to figure out what the issue is.  If it is a bug, I will try to fix the issue in the next release.  Please contact me via the author's web site link.

== Screenshots ==

1. Sample screenshot of Twig in action.
2. Screenshot of the Settings page in Admin.
3. You decide how the Tweets look on your front page with the use of templates.

== Configuration ==

To access the settings page, navigate to the WordPress Plugins page and click Settings next to the Twig (plugin must be activated for this to appear).

= Configuration Fields =

* <strong><tt>Twitter Username</tt></strong> - The username (not e-mail) that is associated with your Twitter account.
* <strong><tt>Twitter Password</tt></strong> - The password that is associated with your Twitter account.
* <strong><tt>Refresh Interval</tt></strong> - The time in minutes between updating the cache from Twitter (do not set this number too low, else you risk being banned by Twitter). Recommended: 30 minutes.
* <strong><tt>Tweet limit between posts</tt></strong> - If you tweet more oftan than you post to your blog, this option allows you to limit the number of tweets show between each post to avoid congestion.
* <strong><tt>Date Format</tt></strong> - Pattern to format the Tweet date on your blog. For a reference, please see this page. Example: M j, Y @ h:i A
* <strong><tt>Only include Tweets that contain text</tt></strong> - Will only display Tweets that contain a specific string, useful for "hashtags" or "bangtags".
* <strong><tt>Hide @replies?</tt></strong> - Would you like your @replies to other Twitter users displayed?
* <strong><tt>Prepend username to tweets?</tt></strong> - Add your Twitter username to the beginning of Tweets?
* <strong><tt>Trim hyphens from tweet?</tt></strong> - If you prepend your Tweets with a hyphen, this can optionally remove it (for example, if you tweet says, "- I'm taking a nap."" it would display as "I'm taking a nap."")
* <strong><tt>Tweet Template</tt></strong> - The template used to format and display each tweet.
* <strong><tt>Show/Hide Template</tt></strong> (optional) - The template for the Delete button/link, displayed to those with Administrator privileges to quickly delete a tweet from the cache.
* <strong><tt>Clear Cache</tt></strong> - Clear the most recent entries in the cache and refresh them from Twitter. This may be useful if you are experience data issues.

== Planned Enhancements ==

* Localization
* Ability to delete tweets from cache via Admin panel
* Support for multiple feeds
* Admin page redesign

`<?php code(); // goes in backticks ?>`
