=== Wordpress Post Distance Filter ===
Contributors: niksudan
Tags: posts, archive, distance, location, filter
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Filter posts by distance

== Description ==

Filter posts by distance from a specified location.

This plugin implements simplistic functionality to WordPress posts by enabling archives to be sorted via distances from a specified location when certain URL parameters are detected. You are able to show how far the post's distance is, and restrict results by a certain radius.

Supports various units of measurement and is customisable too.

Examples of use could be a job search or a store locator.

== Installation ==

= Setup =

Upload the <code>wp-post-distance-filter</code> folder to your plugins directory, then activate the plugin in the Plugins menu. An option should appear under Settings.

= Changing Options =

On the options page you'll have a few settings that you can change to make the plugin work to your liking.

- You can disable all location functionality by disabling it
- You can change the unit of measurement
- You can change the URL parameters
- You can change the name of various <code>meta_keys</code> that the plugin uses when calculating the distance for each post

= Specifying Post Locations =

To make a post able to be filtered, you must specify the location using a custom field. Enable this at the top of the page.

Then enter the name of your location <code>meta_key</code> and specify the value you want it to show. After updating the post, two more custom fields should appear - the lat and lng keys if done correctly.

= Filtering Posts =

For any archive page, if the URL location parameter is specified, it will order by distance. You can limit the number of results using the URL radius parameter.

You can show distance information using <code>the_distance();</code> and <code>get_the_distance();</code> within the wordpress loop.

    if (have_posts()) : while (have_posts()) : the_post();

		the_title();
		the_content();
		the_distance();

	endwhile; endif;

== Frequently Asked Questions ==

= My posts aren't filtering =
Make sure that your URL parameters are correct and the plugin is enabled.

= The latitude and longitude aren't calculating =
Make sure nothing else is using the post's lat and lng meta_keys. If they are, consider changing their names on the options page.

= My posts's aren't showing up =
Make sure that you've specified a location

= I can't see the custom field for location =
You need to enable the custom field option by clicking the post dropdown at the very top of the page.

== Screenshots ==

You can see images of the plugin in action on the [GitHub repository](https://github.com/NikSudan/wp-post-distance-filter).

== Changelog ==

= 1.1.1 =
* Nest plugin under the Settings tab instead

= 1.1.0 =
* Improved options page
* Latitude and longitude postmeta are now editable
* Bugfixes

= 1.0.1 =
* Naming conventions

= 1.0 =
* Initial release

== Upgrade Notice ==

-
