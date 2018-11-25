=== Minimal Share Buttons ===
Tags: facebook, share buttons, social media, social sharing, twitter, linkedin
Tested up to: 5.0
Stable tag: 1.0.1
License: GPL3 or later
License URI: https://www.gnu.org/licenses/gpl.html
Contributors: gonzomir, adrian-roselli

A social share plugin that doesn't spy on users and doesn't slow down you site.

== Description ==

Add simple share buttons under your posts or use the widget to add sharing to any widget area your theme provides. This plugin uses simple SVG icons for social network logos and small vanilla JavaScript to allow the user to share the current post or page. Share icons inherit their colours from the theme link colours to match the website design.

Why choose Minimal Share Buttons before other similar plugins?

* Minimal and elegant look that blends with your theme (tested with all latest default WordPress themes as well as with some other popular themes)
* Minimal impact on your site's performance - the plugin loads only a small SVG file with the icons, less than 1k CSS and 3.5к unminified and uncompressed JavaScript - most of it to make SVG icons work in old browsers.
* Doesn't spy on your users - the plugin doesn't load any thitd-party scripts that record your user's activity on your site, doesn't set or read any cookies.
* GDPR-hasle-free - since the plugin doesn't leak personal information to third parties, this makes it easier for website owners to comply with the European privacy regulations.
* Accessibility - the share links have labels, read by screen readers and visible for keyboard users.
* Gutenberg and WordPress 5.0 ready - the plugin provides block that displays the share buttons so that authors can place them wherever they want in the post content.

== Installation ==

1. Extract the zip file and upload the folder `minimal-share-buttons` to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Share Options screen to select which social networks you want your content to be shared on, and on which post types the share buttons to appear.

== Usage ==

There are three ways of displaying the share buttons on a post or page:

1. Force them to display under the content of the post by checking the relevant checkboxes in the Display settings sections on the plugin settings screen.
2. Add Share widget to the sidebar or another widget area.
3. Use the Gutenberg block to add the share buttons whereever you want in the post content.

== Changelog ==

= 1.0.1 =
* Fix a fatal error in WordPress 5.0 RC.

= 1.0 =
* Gutenberg block.
* Code style updates, output escaping, etc.
* Localisation updates.

= 0.6 =
* Accessibility fix and enhancement thanks to @adrian-roselli

= 0.5 =
* Update Twitter share URL to include permalink of page/post.
* Add rel="noopener" to share links, nullify window.opener in javascript.

= 0.4 =
* Fix $this reference when registering settings page in settings.php

= 0.3 =
* Refactor settings.php into a class.
* Add singleton class to hold social networks list and allow manipulation through a filter.

= 0.2 =
* Add filters that allow theme developers to use their own SVG sprite and rewrite icons.

= 0.1.1 =
* Fix old PHP versions compatibility issue

= 0.1 =
Initial release

== Theme developers ==

If your theme uses SVG icons, combined into a SVG sprite, and your sprite has icons for Facebook, Twitter, Google+ and LinkedIn, there are two filters you can use to replace the icons, provided by the plugin, with yours. The results of the two filters are concatenated with a hash between them and passed through `esc_url` before output.

Another filter allows manipulation of the array of social networks.

===msb_sprite_url===

The filter is applied to the URL of the sprite image and the filter function should return the URL (without the hash sign) of an SVG sprite image, consisting of icons in `symbol` elements.

===msb_icon_name===

The filter is applied to the icon name before concatenating it to the sprite URL. It should return the ID of the icon symbol in the sprite.

===msb_socials===

The filter is applied to the default list of social networks and allows adding or removing socials networks. The array of social networks is associative array, the key is used for the option name on settings page and for the icon ID, and the value is associative array with three elements - `field_label` (the label of the field in settings), `button_label` (the label of the button for screenreader users), and `share_url` (the URL for sharing links). The `share_url` is passed through `sprintf` with two params - the URL of the current page and the title of the page. Example:

    function my_add_xing( $socials ){

      $socials['xing'] = array(
        'field_label' => __( 'Xing', 'mytheme' ),
        'button_label' => __( 'Share on Xing', 'mytheme' ),
        'share_url' => 'https://www.xing.com/spi/shares/new?url=%1$s&title=%2$s'
      );

      return $socials;

    }
    add_filter( 'msb_socials', 'my_add_xing' );


== Credits ==

* SVG Icons from (Font-Awesome-SVG-PNG)[https://github.com/encharm/Font-Awesome-SVG-PNG], MIT licence
* Banner image by (heinzremyschindler on pixbay)[https://pixabay.com/en/share-play-words-2482016/], (Creative Commons CC0)[https://creativecommons.org/publicdomain/zero/1.0/deed.en]
* Plugin icon based on (work by Nathan Diesel from the Noun Project)[https://thenounproject.com/term/share/107273/], (Creative Commons CC-BY)[http://creativecommons.org/licenses/by/3.0/us/]
