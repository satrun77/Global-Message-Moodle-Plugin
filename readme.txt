=== Global Message "Administrator Plugin" ===

Author:    Mohamed Alsharaf (mohamed.alsharaf@gmail.com)
Website:   http://jamandcheese-on-phptoast.com
Copyright: 2011-2012 Mohamed Alsharaf
License:   http://www.gnu.org/copyleft/gpl.html
Version:   2.0.4

== Changelog: ==
2.0.0 - First version for Moodle 2.1
2.0.2 - Fixed missing close button from the popup box
      - New requirements from Moodle.org, database tables for local plugin must start with 'local_'. This only affect new installation.
2.0.3 - Fixed database table names
      - Minor bug fixes
2.0.4 - Minor bug fixes

== Installation ==
1. Copy and paste the folder globalmessage into the local directory. If you don't have a local directory then create one in the Moodle root folder.

2. Log into your Moodle site as an admin user.

3. Go to the notifications page to install this plugin. The plugin will install 3 global settings and 3 database tables.

4. The plugin location is Site Administration --> Front Page --> Global Message

7. Open your theme config in /theme/[theme name] and place the following at the end of the file. If you have a closing tag ?> then make sure the code line below is before it.
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

8. Create a new file in the same direcotry of your theme renderers.php and then place the following code.

class theme_[theme name]_core_renderer extends core_renderer
{
    public function standard_end_of_body_html() {
        global $CFG;

        include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
        moo_globalmessage::show_message();

        return parent::standard_end_of_body_html();
    }
}

9. Replace [theme name] with the name of your theme.
10. If the file renderers.php exists in your theme then place the following code before the last '}' of the theme core renderer "theme_[theme name]_core_renderer" if exists.
If the theme core renderer does not exists in the file, then place the code in step 8 at the top of the file after <?php
    public function standard_end_of_body_html() {
        global $CFG;

        include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
        moo_globalmessage::show_message();

        return parent::standard_end_of_body_html();
    }

== Upgrade ==

1. Make a backup of your folder /local/globalmessage/

2. Disable global message.

2. Copy and paste the folder globalmessage into the local directory to override all of the existing plugin files.

3. Go to the notifications page to upgrade this plugin.

4. Enable global message.
