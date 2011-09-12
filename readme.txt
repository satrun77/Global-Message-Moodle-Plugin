=== Global Message "Administrator Plugin" ===

Author:    Mohamed Alsharaf (mohamed.alsharaf@gmail.com)
Website:   http://jamandcheese-on-phptoast.com
Copyright: 2011-2012 Mohamed Alsharaf
License:   http://www.gnu.org/copyleft/gpl.html
Version:   2.0.0

== Changelog: ==
2.0.0 - First version for Moodle 2.1

== Installation ==
1. Copy and paste the folder globalmessage into the local directory. If you don't have a local directory then create one in the Moodle root folder.

2. Log into your Moodle site as an admin user.

3. Go to the notifications page to install this plugin. The plugin will install 3 global settings and 3 database tables.

4. The plugin location is Site Administration --> Front Page --> Global Message

7. Open your theme layout in /theme/[theme name]/layout and place the following code just before </body> tag and after <?php echo $OUTPUT->standard_end_of_body_html() ?>

<?php
include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
moo_globalmessage::show_message();
?>

== Upgrade ==

1. Make a backup of your folder /local/globalmessage/

2. Copy and paste the folder globalmessage into the local directory to override all of the existing plugin files.

3. Go to the notifications page to upgrade this plugin.
