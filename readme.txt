=== Global Message "Administrator Plugin" ===

Author:    Mohamed Alsharaf (mohamed.alsharaf@gmail.com)
Website:   http://jamandcheese-on-phptoast.com
Copyright: 2011-2012 Mohamed Alsharaf
License:   http://www.gnu.org/copyleft/gpl.html
Version:   1.0.2

== Changelog: ==
1.0.0 - First version
1.0.1 - Fixed the issue with Moodle standard html editor
1.0.2 - Some language changes

== Installation ==
1. Copy and paste the folder globalmessage into the local directory. If you don't have a local directory then create one in the Moodle root folder.

2. Locate the file /local/settings.php
    a. If the file does not exist, then
        - Copy the /local/globalmessage/settings.php into /local/
    b. If the file exists, then
        - Paste all of the content of /local/globalmessage/settings.php at the bottom of your /local/settings.php

3. Locate the file /local/db/upgrade.php
    a. If the file does not exist, then 
        - Copy the file /local/globalmessage/upgrade.php into local/db/
        - Copy the file /local/globalmessage/version.php inot local/db/

    b. If the file exists, then
        - Copy the following code and paste it into the file /local/db/upgrade.php

        if ($result && $oldversion < [new version]) {
            include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
            $result = moo_globalmessage::install($db);
        }

        - Increase local version number in /local/version.php

4. Log into your Moodle site as an admin user.

5. Go to the notifications page to install this plugin. The plugin will install 3 global settings and 3 database tables.

6. The plugin location is Site Administration --> Front Page --> Global Message

7. Open footer.html in your theme folder /theme/ and place the following code just before </body> tag.

<?php
include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
moo_globalmessage::show_message();
?>

== Upgrade ==

1. Make a backup of your folder /local/globalmessage/

2. Copy and paste the folder globalmessage into the local directory to override all of the existing plugin files.

3. Copy the following code and paste it into the file /local/db/upgrade.php

if ($result && $oldversion < [new version]) {
    include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
    $result = moo_globalmessage::upgrade($oldversion, $db);
}

4. Increase local version number in /local/version.php
