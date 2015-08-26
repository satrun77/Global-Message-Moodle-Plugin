# Global Message "Administrator Plugin"

```
Author:    Mohamed Alsharaf (mohamed.alsharaf@gmail.com)
Website:   http://my.geek.nz
Copyright: 2011-2012 Mohamed Alsharaf
License:   http://www.gnu.org/copyleft/gpl.html
Version:   2.1.4
```

## Changelog:
- 2.0.0
  	- First version for Moodle 2.1
- 2.0.2 
	- Fixed missing close button from the popup box
    - New requirements from Moodle.org, database tables for local plugin must start with 'local_'. This only affect new installation.
- 2.0.3 
	- Fixed database table names
    - Minor bug fixes
- 2.0.4
	- Minor bug fixes
- 2.1.0
 	- New section under 'Info' to manage custom rules. Install/Uninstall custom rules.
    - Style sheet modifications
    - JavaScript modifications & extract shared code into a separate file base.js
    - Custom rules class interface modifications
    - New Methods:
    	- public function is_installed();
        - public function install();
        - public function uninstall();
        - Method definition changes from validate() to validate($options = null)  
   - Language string modifications.
   - Removed unused code.
- 2.1.1
	- Compatible with Moodle 2.4.*
- 2.1.2
	- Bug fixes in info/about page
- 2.1.3
	- Compatible with Moodle 2.6.*
- 2.1.4
	- Compatible with Moodle 2.7.*
- 2.1.5
	- Compatible with Moodle 2.8.*

## Installation

1. Copy and paste the folder globalmessage into the local directory. If you don't have a local directory then create one in the Moodle root folder.

2. Log into your Moodle site as an admin user.

3. Go to the notifications page to install this plugin. The plugin will install 3 global settings and 3 database tables.

4. The plugin location is `Site Administration --> Front Page --> Global Message`

7. Open your theme config in `/theme/[theme name]` and place the following at the end of the file. If you have a closing tag `?>` then make sure the code line below is before it.
`$THEME->rendererfactory = 'theme_overridden_renderer_factory';`

8. Create a new file in the same direcotry of your theme renderers.php and then place the following code.

```
require_once(__DIR__ . '/../bootstrapbase/renderers.php');

class theme_[theme name]_core_renderer extends theme_bootstrapbase_core_renderer
{
    public function standard_end_of_body_html() {
        global $CFG;

        include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
        moo_globalmessage::show_message();

        return parent::standard_end_of_body_html();
    }
}
```
9. Replace [theme name] with the name of your theme.
10. If the file renderers.php exists in your theme then place the following code before the last `}` of the theme core renderer `theme_[theme name]_core_renderer` if exists.

```
    public function standard_end_of_body_html() {
        global $CFG;

        include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
        moo_globalmessage::show_message();

        return parent::standard_end_of_body_html();
    }
```

10. If the theme core renderer does not exists in the file, then place the code in step 8 at the top of the file after `<?php`

## Upgrade

1. Make a backup of your folder /local/globalmessage/

2. Disable global message.

2. Copy and paste the folder globalmessage into the local directory to override all of the existing plugin files.

3. Go to the notifications page to upgrade this plugin.

4. Enable global message.

## Q/A & Help information

#### Q 1) Issue [#7](https://github.com/satrun77/Global-Message-Moodle-Plugin/issues/7): Corrupted CSS theme after completing the installation steps from 8 to 10

Instead of the steps 8 to 10, place the following code above the call to `echo $OUTPUT->course_content_header();`

```
include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
moo_globalmessage::show_message();
```

## Thank You for your contribution

- Spanish translation (By the Moodler "Nacho Aguilar")
- German translation (By the Moodler "Joachim Vogelgesang")
- Portuguese/Brazil translation (By the Moodler "felipe camboa")
- General contributions:
	- Ben Tindell
