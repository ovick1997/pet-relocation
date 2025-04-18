=== Pet Relocation Form ===
Contributors: ovick1997
Tags: pet relocation, form, multi-step, admin, pets
Requires at least: 5.0
Tested up to: 6.6
Stable tag: 1.0.11
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A multi-step form plugin for managing pet relocation requests, including pet details, travel information, and additional services. Use the shortcode [pet_relocation_form] to display the form on any page or post.

== Description ==

The Pet Relocation Form plugin provides a user-friendly, multi-step form for collecting pet relocation requests. It allows users to input details for multiple pets, specify travel locations and dates, and select additional services like grooming or post-arrival support. Admins can view and manage submissions in the WordPress dashboard with a detailed view for each request.

Features:
- Multi-step form with pet information, location, and service selection.
- Support for multiple pets with optional additional details.
- Admin dashboard to view and manage requests.
- AJAX-based form submission with success notification and auto-reload.
- Beautifully organized "View Details" page in the admin area.

== Installation ==

1. Upload the `pet-relocation` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place the shortcode `[pet_relocation_form]` in a page or post to display the form.
4. Visit the "Pet Relocation" menu in the WordPress admin to manage requests.

== Frequently Asked Questions ==

= How do I add the form to my site? =
Use the shortcode `[pet_relocation_form]` in any page or post.

= Can I add multiple pets? =
Yes, click "Add Another Pet Information" in Step 1 to include additional pets.

= What happens after submission? =
The page reloads after 5 seconds to show the updated request list, and an email notification is sent to the admin.

= How do I view submission details? =
Go to the "Pet Relocation" menu in the admin area and click "View Details" for any request.

== Screenshots ==

1. Step 1: Pet Information form.
2. Step 2: Location and Travel details.
3. Step 3: Additional Services selection.
4. Admin dashboard with request list.
5. Detailed view of a submission in the admin modal.

== Changelog ==

= 1.0.11 =
* Fixed AJAX submission error caused by a typo in field sanitization.
* Added enhanced error logging for better debugging.

= 1.0.10 =
* Updated plugin header with improved description and shortcode instructions.
* Added license comments to all files for WordPress.org compliance.

= 1.0.9 =
* Added WordPress.org guideline format with readme.txt.
* Updated plugin header with required fields.

= 1.0.8 =
* Fixed table creation issue on plugin activation.
* Improved error logging for debugging.

= 1.0.7 =
* Initial release with multi-step form and admin functionality.

== Upgrade Notice ==

= 1.0.11 =
This update fixes an AJAX submission error and improves debugging. Recommended for all users.

== Arbitrary section ==

For support or contributions, visit the plugin repository at https://github.com/ovick1997/pet-relocation or contact the author at mdovick1952@gmail.com.
