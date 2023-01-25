=== Intercom Live chat and Lead generation by 79mplus ===
Contributors: 79mplus
Donate link: https://www.79mplus.com/donate
Tags: intercom, chat, lead, email, newsletter, marketing, user base, grow, communication
Requires at least: 5.0
Tested up to: 6.1.1
Stable tag: trunk
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Offers a live chat (by Intercom), a lead generation form and a wide range of extensions to grow your user base using Intercom.

== Description ==

The easiest and most extendable WordPress plugin for Intercom. This lets you offer a live chat bubble, a lead generation form for listing your users with Intercom and offers a wide range of extensions for many popular plugins.

[youtube https://www.youtube.com/watch?v=sCTiod2glu0]

= Features =

Here are some of its features:

- Use Anywhere: Put on page, post, product or wherever!
- Use OAuth to connect to Intercom
- Enable the chat bubble in footer
- No coding required
- Instructions provided for everything to make it easy for you
- Easy to configure: Takes 5-10 minutes.
- Easy to use
- Extendable: Pro add-ons available for Contact Form 7, WooCommerce, Gravity Forms and many more.

= Grow and Track =
Grow your user base and keep track of users. Helps you to gather users and grow your user base. Intercom further helps to keep track of your users.

= Use OAuth to connect to Intercom =
No need to create your own app to get the access token. Just click the OAuth connect button and give our app the necessary permissions to interact with your Intercom account.

= Subscription Form =
You will get a standard subscription form to get users in, like any other subscription form. You can place it wherever you want, in a post or page.

= Extensions available =
Extra add-ons or extensions are available so that you can extend the functionality with popular plugins. We currently offer add-ons for:

E-commerce Integration:
- [WooCommerce](https://www.79mplus.com/product/mplus-intercom-wc/)

Form Integration:
- [Contact Form 7](https://www.79mplus.com/product/mplus-intercom-cf7/)
- [Caldera Forms](https://www.79mplus.com/product/mplus-intercom-cf/)
- [Ninja Forms](https://www.79mplus.com/product/mplus-intercom-nf/)
- [Gravity Forms](https://www.79mplus.com/product/mplus-intercom-gf/)
- [WeForms](https://www.79mplus.com/product/mplus-intercom-weforms/)
- [WP Forms](https://www.79mplus.com/product/mplus-intercom-wpforms/)
- [Formidable Forms](https://www.79mplus.com/product/mplus-intercom-ff/)

Multi Vendor System:
- [Dokan](https://www.79mplus.com/product/mplus-intercom-dokan/)

Download Management:
- [Easy Digital Downloads](https://www.79mplus.com/product/mplus-intercom-edd/)

Extra Features:
- [Tags](https://www.79mplus.com/product/mplus-intercom-tags/) (Free)
- [Events](https://www.79mplus.com/product/mplus-intercom-events/) (Free)

They are similarly easy to use and we make sure instructions are provided for all.
Details here: [https://www.79mplus.com/intercom-subscription/](https://www.79mplus.com/intercom-subscription/)

= Demo Site =
Interested? Check out the plugin in action on our demo site:
[https://intercom.demo.79mplus.com/](https://intercom.demo.79mplus.com/)

= Documentation =
If you need detailed help, we’ve got you covered. Check here:
[https://docs.79mplus.com/intercom-subscription-base-plugin/](https://docs.79mplus.com/intercom-subscription-base-plugin/)

= What is Intercom? =
[Intercom](https://www.intercom.io/) is a fundamentally new way for internet businesses to communicate with customers, personally, at scale. It’s a customer communication platform with a suite of integrated products for every team – including sales, marketing, product, and support. Their products enable targeted communication with customers on your website, inside your web and mobile apps, and by email.

= Built with Developers in Mind =
Most extendable, adaptable, and open source — Mplus Intercom Subscription is created with developers in mind. Contribute on [GitHub](https://github.com/79mplus/Mplus-Intercom-Subscription).

== Installation ==

Easy way:
1. Go to **WP Admin - Plugins - Add New**
2. Search for "mplus intercom"
3. Install the one from 79mplus, then Activate it

or Manual way:
1. Download the plugin zip file
2. Extract it
3. Upload the plugin directory to **wp-content/plugins**
4. Activate **Mplus Intercom Subscription** plugin from **WP Admin - Plugins**

= Configuration =

After you activated the plugin, you can go to WP Admin - Intercom Subscription menu and enter the Intercom Access Token there.

Then take 2 minutes to add this shortcode in the page you want the form to appear:
`[mplus_intercom_subscription]`

This should be all needed for a basic setup. If you are interested in more changes, check the other settings.

Optional: If you want to enable the ability for your users to create a company for the company selection field, you can create a new page with this shortcode below:
`[mplus_intercom_subscription_company]`
Then select it on settings page.
The link will appear below the company selection field (see screenshot).

== Frequently Asked Questions ==

= How can I install the plugin? =

Easiest way is to go to WP Admin - Plugins - Add New, and search for "mplus intercom". You will have an option to Install and Activate it.
Afterwards, you just need 5 minutes to put the Access Token in the Intercom Subscription settings page and put the `[mplus_intercom_subscription]` shortcode in any page or post you like.

= Is there any third party plugins required? =

For the base plugin (this one), no other plugin is required. It takes care of itself. For the addon plugins however, you may need the plugin for which you bought it for.
For example, to use "Mplus Intercom Subscription - WooCommerce" addon, you would need WooCommerce to be installed.

= How can I get the Intercom Access Token? =

To create your Access Token, go to: [https://app.intercom.com/developers/_](https://app.intercom.com/developers/_) and then click 'Get an Access Token'. Details here: [https://developers.intercom.com/docs/personal-access-tokens#section-creating-your-access-token](https://developers.intercom.com/docs/personal-access-tokens#section-creating-your-access-token)

= Can I customize the form? =

Yes, you can include styles for the form on your theme to customize it.

== Screenshots ==

1. Form on the frontend
2. Settings page
3. Settings to enable Company Integration (optional)
4. Company field on the form
5. Company create page

== Changelog ==

= 2.0.0 =
* Implemented OAuth connectivity to Intercom.
* Implemented Intercom chat option.
* The access token given in the old version will no longer work. Site admin needs to connect to Intercom again using OAuth.


= 1.1.0 =
* Compatible with 2.3 latest version intercom api.
* Updated intercom php SDK file.
* Update user, lead and company registration add and update porcess.

= 1.0.27 =
* Fix PHP error.
* Update get access token page link in settings page.

= 1.0.26 =
* Fix new company not registered properly.

= 1.0.25 =
* Updated intercom php SDK file
* Fix user not created issue.

= 1.0.24 =
* Updated the new logo and banner.
* Fix for icon not showing on admin sidebar.
* Changed "Create it" to "Create here" on company field description.

= 1.0.22 =
* Added Honeypot Spamtrap feature to stop spams.
* Updated company registration field names and added helpful hints.
* Updated screenshots.

= 1.0.20 =
* Added support for Company data integration with Intercom API.
* Added appropriate settings for company selection fields and company registration page.
* Added new shortcode for company registration page.
* Minor Code Cleanup.
* Updated screenshot.
* Updated Readme.

= 1.0.18 =
* Some internal code changes.
* Added consent checkbox option.
* Added some new, convenient hooks.
* Added custom admin menu icon.
* Changed admin menu label and simplified some instances of plugin name.
* Updated some URLs.
* Updated/added screenshot.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.23 =
Upgrade to fix the menu image not showing issue.

= 1.0.20 =
Upgrade to enjoy company integration feature on Intercom API/Dashboard.

= 1.0.18 =
Option name for API Access Token has been changed. If API Access Token appears blank, please put it again manually. Has important changes. Please upgrade immediately.

= 1.0 =
(Initial release. Please Install.)

= 2.0.0 =
Implemented OAuth connection to intercom and chat option.
