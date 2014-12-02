=== Wordpress Post Distance Filter ===
Contributors: niksudan
Tags: posts, archive, distance, location, filter
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Filter posts by distance

== Description ==

Filter Wordpress posts by distance from a location, and optionally specify a maximum distance. Done through URL GET parameters. Customise a few options and you're ready to have it implemented.

== Installation ==

Upload the wp-distanceFilter folder to your plugins directory, then activate the plugin in the Plugins menu. An options menu should become available beneath Settings.

![Activation](http://i.imgur.com/eJ8zZ8F.png)

== Changing Options ==

On the options page you'll have a few settings that you can change to make the plugin work to your liking.

- You can disable all location functionality by disabling it
- You can change the name of the <code>meta_key</code> that the plugin looks for when calculating the latitude and longitude
- You can change the unit of measurement
- You can change the URL parameters

![Options](http://i.imgur.com/DRHXa5o.png)

== Specifying Post Locations ==

To make a post able to be filtered, you must specify the location using a custom field. Enable this at the top of the page.

![Custom Field Enable](http://i.imgur.com/EijS0Hx.png)

Then enter the name of your location <code>meta_key</code> and specify the value you want it to show. After updating the post, two more custom fields should appear - <code>lat</code> and <code>lng</code> if done correctly.

![Custom Field](http://i.imgur.com/Bcaupq7.png)

== Filtering Posts ==

For any archive page, if the URL location parameter is specified, it will order by distance. You can limit the number of results using the URL radius parameter.

![Plugin in Action](http://i.imgur.com/BDuqajL.png)

You can show distance information using <code>the_distance();</code> and <code>get_the_distance();</code> within the wordpress loop.

    if (have_posts()) : while (have_posts()) : the_post();

		the_title();
		the_content();
		the_distance();

	endwhile; endif;

== Changelog ==

= 1.0 =
* Initial release
