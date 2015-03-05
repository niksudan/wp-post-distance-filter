WordPress Post Distance Filter
=================

Filter WordPress posts by distance from a location, and optionally specify a maximum distance.

[Download for WordPress here!](https://wordpress.org/plugins/wp-post-distance-filter/)

This plugin implements simplistic functionality to WordPress posts by enabling archives to be sorted via distances from a specified location when certain URL parameters are detected. You are able to show how far the post's distance is, and restrict results by a certain radius.

## Setting Up

Activate the plugin in the Plugins menu. An option should appear under Settings.

![Activation](http://i.imgur.com/7BN03W3.png)

## Changing Options

On the options page you'll have a few settings that you can change to make the plugin work to your liking.

- You can disable all location functionality by disabling it
- You can change the unit of measurement
- You can change the URL parameters
- You can change the name of various <code>meta_keys</code> that the plugin uses when calculating the distance for each post

![Options](http://i.imgur.com/6iM8OR7.png)

## Specifying Post Locations

To make a post able to be filtered, you must specify the location using a custom field. Enable this at the top of the page.

![Custom Field Enable](http://i.imgur.com/EijS0Hx.png)

Then enter the name of your location <code>meta_key</code> and specify the value you want it to show. After updating the post, two more custom fields should appear - the lat and lng keys if done correctly.

![Custom Field](http://i.imgur.com/Bcaupq7.png)

## Filtering Posts

For any archive page, if the URL location parameter is specified, it will order by distance. You can limit the number of results using the URL radius parameter.

![Plugin in Action](http://i.imgur.com/stKQHan.png)

You can show distance information using <code>the_distance();</code> and <code>get_the_distance();</code> within the WordPress loop.

    if (have_posts()) : while (have_posts()) : the_post();

		the_title();
		the_content();
		the_distance();

	endwhile; endif;
		
And that's it!
