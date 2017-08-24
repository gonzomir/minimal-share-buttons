=== Minimal Share Buttons ===
Tags: facebook, share buttons, social media, social sharing, twitter, linkedin
Tested up to: 4.8.1
Stable tag: 0.1.1
License: GPL3 or later
License URI: https://www.gnu.org/licenses/gpl.html

A social share plugin that doesn't spy on users and doesn't slow down you site.

== Description ==

Add simple share buttons under your posts or use the widget to add sharing to any widget area your theme provides. This plugin uses simple SVG icons for social network logos and small vanilla JavaScript to allow the user to share the current post or page. Share icons inherit their colours from the theme link colours to match the website design.

Why choose Minimal Share Buttons before other similar plugins?

* Minimal and elegant look that blends with your theme (tested with all latest default WordPress themes as well as with some other popular themes)
* Minimal impact on your site's performance - the plugin loads only a small SVG file with the icons, less than 1k CSS and 3.5к unminified and uncompressed JavaScript - most of it to make SVG icons work in old browsers.
* Doesn't spy on your users - the plugin doesn't load any thitd-party scripts that record your user's activity on your site, doesn't set or read any cookies.

== Installation ==

1. Extract the zip file and upload the folder `minimal-share-buttons` to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Share Options screen to select which social networks you want your content to be shared on, and on which post types the share buttons to appear.

== Theme developers ==

If your theme uses SVG icons, combined into a SVG sprite, and your sprite has icons for Facebook, Twitter, Google+ and LinkedIn, there are two filters you can use to replace the icons, provided by the plugin, with yours:

* **msb_sprite_url** - the filter is applied to the URL of the sprite image and the filter function should return the URL (without the hash sign) of an SVG sprite image, consisting of icons in `symbol` elements.
* **msb_icon_name** - the filter is applied to the icon name before concatenating it to the sprite URL. It should return the ID of the icon symbol in the sprite.
