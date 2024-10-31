=== Shabbos and Yom Tov ===
Contributors: shabboscommerce
Tags: woocommerce, close cart, checkout, jewish holiday, Shabbat, 
Requires at least: 4.3.0
Tested up to: 4.9.7
Requires PHP: 5.2.4
Stable tag: 1.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
A shortcode to automatically hide shopping cart or other content on Shabbos and Yom Tov and disply a message to the user.


== Description ==
 
Automatically hide shopping cart or other content on Shabbos and Yom Tov and disply a message to the user. Alternately you can use it on your contact us page to display a message on Yom Tov. 

The shortcode should be like `[Shabbos_and_YomTov]Weekday content.[/Shabbos_and_YomTov]`. See __Attributes__ and __Examples__ below. 

This plugin will work according to your timezone that you set and _not_ according to the users timezone (ie. a non-Jewish costomer can buy when it is Shabbos by him if by you it is not shabbos).

This plugin does not close your site on Shabbos, only the data enclosed in the shortcode. To close tour entire site for Shabbos use a plugin as [WP-Shabbat](https://wordpress.org/plugins/wp-shabbat/). However it is possible to do so with this plugin see [Frequently Asked Questions](#faq).


== Installation ==
 
1. Upload `Shabbos-and-YomTov.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set the defaults in "Settings>Shabbos and Yom Tov"
4. Place the shortcode wherever you want on your site. See __Attributes__ and __Examples__ sections.  
   The shortcode should be like `[Shabbos_and_YomTov]Weekday content.[/Shabbos_and_YomTov]`.

Note: This plugin uses Zemanim API (https://wyrezmanim.herokuapp.com/api/zmanim), for more information see [https://wyrezmanim.herokuapp.com/help](https://wyrezmanim.herokuapp.com/help) and [https://kosherjava.com](https://kosherjava.com).

Icon by: Shabbat by Studio GLD from the Noun Project


== Frequently Asked Questions ==
 
= Can I use this plugin to close my entire site on Shobbos? =

This plugin was not created for this intent but rather to close the checkout counter. [WP-Shabbat](https://wordpress.org/plugins/wp-shabbat/) was created for this.
However you can still do this using this plugin by editing your theme or by adding a function to your [site's plugin](https://www.wpbeginner.com/beginners-guide/what-why-and-how-tos-of-creating-a-site-specific-wordpress-plugin/). Remember this plugin will on your time zone and location that you set not of the user of you site.
In both examples a cookie is set on weekdays so it not have to check on every page if it is Shabbos or Yom Tov, only once in 10 minutes.

Note: If you have a commercial theme do not edit your theme files because it would be deleted when you update your theme, rather make a child theme or do this in a plugin).

**In your theme, it should look something like this:**


	
	$atts = array ();
	
	if (!$_COOKIE['weekday'] == 'true'){
	    if (!$shabbosMessage = Shabbos_and_YomTov($atts, FALSE)){
	        //Set a weekday cookie
	        setcookie ('weekday', 'true', time() + 600, '/');
	    }
	}
	
	if ($shabbosMessage){
	    echo $shabbosMessage;
	} else {
	        
	        the_post(); 
	        // all post info ...
	}
	


**In a plugin:**
Note: The following plugin will only hide the content of the post.


	
	function check_if_shabbos($content){
	
	    if ($_COOKIE['weekday'] == 'true'){
	        return $content;
	    }
	    
	    // Here you can set attributes if it is different then set at 'Settings>Shabbos and Yom Tov'.
	    $atts = array ();
	    
	    
	    if ($shabbosMessage = Shabbos_and_YomTov($atts, FALSE)){
	        return $shabbosMessage;
	    } else {
	    
	        //Set a weekday cookie
	        setcookie ('weekday', 'true', time() + 600, '/');
	        
	        return $content;
	    
	    }
	}
	
	add_filter( 'the_content', 'check_if_shabbos');
	
	


= Ask a question =
 
Send your question to shabboscommerce@gmail.com.
 
== Screenshots ==
 
1. 
 
## Attributes

See __Examples__ below.

Make sure to set the first 3 attributes.

Make sure to use the `testdate` attribute to make sure the shortcode works correctly.

**timezone**
_Definition_: The timezone of your location. Must be a timezone from <http://php.net/manual/en/timezones.php>.  
              From version 1.8 can now use time zone abbreviations.
_Default value_: 'America/New_York'

**latitude**
_Definition_: The latitude of your location.
_Default value_: '40.63'

**longitude**
_Definition_: The longitude of your location.
_Default value_: '-73.98'


**passover**
_Definition_: How should your site display Passover.
_Default value_: 'Passover'

**shavuos**
_Definition_: How should your site display Shavuos.
_Default value_: 'Shavuos'

**sukkos**
_Definition_: How should your site display Sukkos.
_Default value_: 'Sukkos'

**rosh_hashanah**
_Definition_: How should your site display Rosh Hashanah.
_Default value_: 'Rosh Hashanah'

**yom_kippur**
_Definition_: How should your site display Yom Kippur.
_Default value_: 'Yom Kippur'

**shabbos_msg**
_Definition_: What message should be displayed on Shabbos.
_Default value_: 'We're sorry but we are currently closed due to Shabbos.'

**yom_tov_msg**
_Definition_: What message should be displayed on Yom Tov. (%yomtov% will be replaced with the current Yom Tov)
_Default value_: 'We're sorry but we are currently closed due to %yomtov%.'

**chol_hamoed**
_Definition_: Use 'open' or 'closed'.
_Default value_: 'closed'

**candle**
_Definition_: Candle lighting time, in minutes before sunset.
_Default value_: '15'

**dusk**
_Definition_: Time when Shabbos ends, in minutes after sunset.
_Default value_: '72'

**class**
_Definition_: To add a class for the Shabbos and Yom Tov message.
_Default value_: NULL

**one-day**
_Definition_: If you are in Israel set to 'yes'.
_Default value_: 'no'

**testdate**
_Definition_: Use this attribute to see how your site will look on a certain date and time (eg. '5/26/18 8:05 pm'). Make sure to remove.
_Default value_: NULL
** Make sure to remove this attribute when finished testing. **



## Examples

See __Attributes__ above.

1. Basic example:

	`[Shabbos_and_YomTov]Weekday content.[/Shabbos_and_YomTov]`


2. Basic example for Baltimore:

	`[Shabbos_and_YomTov latitude='39.32' longitude='-76.64']Weekday content.[/Shabbos_and_YomTov]`


3. Basic example for Los Angeles:

	`[Shabbos_and_YomTov latitude='34.05' longitude='-118.43' timezone='America/Los_Angeles']Weekday content.[/Shabbos_and_YomTov]`


4. Basic example for Jerusalem:

	`[Shabbos_and_YomTov latitude='31.77' longitude='35.23' timezone='Asia/Jerusalem' one-day='yes']Weekday content.[/Shabbos_and_YomTov]`


5. Woocommerce example to use on Checkout page:

	`[Shabbos_and_YomTov][woocommerce_checkout][/Shabbos_and_YomTov]`


6. Another Woocommerce example to use on Checkout page:

	`[Shabbos_and_YomTov class="woocommerce-error"][woocommerce_checkout][/Shabbos_and_YomTov]`


7. Example to use on Contact Us page:

	`[Shabbos_and_YomTov shabbos_msg="" yom_tov_msg="We are closed today due to %yomtov%."][/Shabbos_and_YomTov]`


8. Example to use on Contact Us page to show until midnight:

	`[Shabbos_and_YomTov shabbos_msg="" yom_tov_msg="We are closed today due to %yomtov%." dusk=360][/Shabbos_and_YomTov]`



== Changelog ==

= 1.9 =
* Timezone validation upgraded.
* Timezones dropdown on admin page.

= 1.8 =
* Time zones are validated.
* Can now use time zone abbreviations.

= 1.7 =
* The Shabbos or Yom-Tov Message can contain shortcodes when the message is saved in "Settings>Shabbos and Yom Tov".

= 1.6 =
* Initial release
