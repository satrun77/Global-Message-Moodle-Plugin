<?php

define('INTERNAL_ACCESS', 1);

/**
 *
 * @package    moo
 * @subpackage globalmessage
 * @copyright  2011 Mohamed Alsharaf
 * @author     Mohamed Alsharaf (mohamed.alsharaf@gmail.com)
 * @version    2.1.2
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class moo_globalmessage
{
    protected $user;
    protected $config;
    protected $course;

    public function __construct(array $configs = null)
    {
        if ($configs !== null) {
            $this->set_configs($configs);
        }
    }

    public function set_configs(array $configs)
    {
        foreach ($configs as $name => $value) {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            }
        }

        return $this;
    }

    public function grab_moodle_globals()
    {
        global $CFG, $USER, $COURSE;

        $this->user = $USER;
        $this->course = $COURSE;
        $this->config = $CFG;

        return $this;
    }

    /**
     * Load a controller
     *
     * @param string $request
     * @return moo_globalmessage_controller
     */
    public function controller($request)
    {
        $requestparts = explode('/', $request);

        // load controller abstract and find the requested controller
        $this->load_file('lib/controller.php');
        if (!$this->load_file('controllers/' . $requestparts[0] . '.php', true)) {
            print_error('invalidpage');
        }

        // controller class name
        $class = 'moo_globalmessage_controller_' . $requestparts[0];

        // setup action call
        if (isset($requestparts[1])) {
            $action = $requestparts[1];
        } else {
            $action = 'index';
            $request = $requestparts[0] . '/index';
        }

        // return the controller
        return new $class($action, $request, $this);
    }

    /**
     * Load a model class
     *
     * @param string $name
     * @return moo_globalmessage_model
     */
    public function model($name)
    {
        $this->load_file('lib/model.php');
        $this->load_file('models/' . $name . '.php');
        $class = 'moo_globalmessage_model_' . $name;
        return new $class($this);
    }

    public function get_user()
    {
        return $this->user;
    }

    public function get_course()
    {
        return $this->course;
    }

    public function get_config($name = null)
    {
        if ($name !== null && isset($this->config->{$name})) {
            return $this->config->{$name};
        }
        return $this->config;
    }

    /**
     * Get current version
     *
     * @return string
     */
    public function get_version()
    {
        $plugin = new \stdClass();
        include __DIR__ . '/../version.php';
        return $plugin->release;
    }

    /**
     * Get plugin base dir
     *
     * @param string $file
     * @return string
     */
    public function get_basedir($file = null)
    {
        return $this->config->dirroot . '/local/globalmessage/' . $file;
    }

    /**
     * Get language string from plugin specific lang dir
     *
     * @param string $name
     * @param string|object $a
     * @return string
     */
    public function get_string($name, $a = null)
    {
        return stripslashes(get_string($name, 'local_globalmessage', $a));
    }

    /**
     * Get language string from moodle core language
     *
     * @param string $name
     * @param string|object $a
     * @return string
     */
    public function get_string_fromcore($name, $a = null)
    {
        return get_string($name, '', $a);
    }

    /**
     * Load a file
     *
     * @param string $file
     * @param boolean $disableerror
     * @return mix
     */
    public function load_file($file, $disableerror = false)
    {
        $filepath = $this->get_basedir($file);
        if (!file_exists($filepath)) {
            if ($disableerror) {
                return false;
            }
            print_error('invalidfiletoload');
        }
        include_once $filepath;
        return true;
    }

    /**
     * setup administrator links and settings
     *
     * @param object $admin
     */
    public static function set_adminsettings($admin)
    {
        $me = new self();
        $me->grab_moodle_globals();
        $context = context_course::instance(SITEID);

        $admin->add('localplugins', new admin_category('globalmessage', $me->get_string('globalmessage')));
        $admin->add('globalmessage', new admin_externalpage('globalmessagemanage', $me->get_string('globalmessagemanage'), $me->get_config('wwwroot') . '/local/globalmessage/index.php?id=' . SITEID, 'moodle/site:config', false, $context));
        $admin->add('globalmessage', new admin_externalpage('globalmessageabout', $me->get_string('globalmessageabout'), $me->get_config('wwwroot') . '/local/globalmessage/index.php?action=about&id=' . SITEID, 'moodle/site:config', false, $context));

        $temp = new admin_settingpage('globalmessagesettings', $me->get_string('globalmessagesettings'));
        $temp->add(new admin_setting_configcheckbox('globalmessageenable', $me->get_string_fromcore('enable'), $me->get_string('globalmessageenabledesc'), 1));
        $temp->add(new admin_setting_configcheckbox('globalmessagedisableforadminrole', $me->get_string('disableforadminrole'), $me->get_string('disableforadminroledesc'), 0));
        $temp->add(new admin_setting_configcheckbox('globalmessagedisableforadminpage', $me->get_string('disableforadminpage'), $me->get_string('disableforadminpagedesc'), 0));

        $admin->add('globalmessage', $temp);
    }

    /**
     * Show message
     *
     * @return void
     */
    public static function show_message()
    {
        $me = new self();
        $me->grab_moodle_globals();

        // message is only available to logged in users
        // and the global message must be enabled
        if (!isloggedin() || !$me->get_config('globalmessageenable')) {
            return;
        }

        $uri = me();
        if ($me->get_config('globalmessagedisableforadminpage') && substr($uri, 0, 6) == '/admin') {
            return;
        }

        // dont show the message if the current user is admin
        // and globalmessagedisableforadminrole is true
        $context = context_system::instance();
        if ($me->get_config('globalmessagedisableforadminrole') && has_capability('local/globalmessage:isadminrole', $context)) {
            return;
        }

        $message = $me->model('message')->get_message_incurrentpage(array(
                    'user' => $me->get_user(),
                    'course' => $me->get_course(),
                    'time' => time()
                ));
        $me->load_file('lib/view.php');
        $view = new moo_globalmessage_view($me);
        echo $view->render_partial('partial/message', array('message' => $message));
    }

    public static function install($db)
    {
        $tables = self::getDbTables();

        foreach ($tables as $table) {
            if (!table_exists($table)) {
                if (!create_table($table)) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function uninstall($oldversion, $db)
    {
        // drop database tables
        $tables = self::getDbTables();
        foreach ($tables as $table) {
            drop_table($table);
        }

        // drop global settings
        unset_config('globalmessageenable');
        unset_config('globalmessagedisableforadminrole');
        unset_config('globalmessagedisableforadminpage');

        return true;
    }

    public static function upgrade($oldversion, $db)
    {
        return true;
    }

    private static function getDbTables()
    {
        $tables = array();

        // globalmessages table
        $table = new XMLDBTable('local_globalmessages');
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('summary', XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null);
        $table->addFieldInfo('description', XMLDB_TYPE_TEXT, 'big', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('created', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('modified', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('status', XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, 0);
        $table->addFieldInfo('design', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, null, null);
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

        $index = new XMLDBIndex('design');
        $index->setAttributes(XMLDB_INDEX_NOTUNIQUE, array('design'));
        $table->addIndex($index);
        $index = new XMLDBIndex('modified');
        $index->setAttributes(XMLDB_INDEX_NOTUNIQUE, array('modified'));
        $table->addIndex($index);
        $tables[] = $table;

        // globalmessages_designs table
        $table = new XMLDBTable('local_globalmessages_designs');
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('height', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, 400);
        $table->addFieldInfo('width', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, 400);
        $table->addFieldInfo('bgcolor', XMLDB_TYPE_CHAR, '100', null, null, null, null, null, null);
        $table->addFieldInfo('bgimage', XMLDB_TYPE_TEXT, 'medium', null, null, null, null, null, null);
        $table->addFieldInfo('bgimageposition', XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null);
        $table->addFieldInfo('bgimagerepeat', XMLDB_TYPE_CHAR, '50', null, null, null, null, null, null);
        $table->addFieldInfo('bordersize', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, 0);
        $table->addFieldInfo('bordercolor', XMLDB_TYPE_CHAR, '100', null, null, null, null, null, null);
        $table->addFieldInfo('bordershape', XMLDB_TYPE_CHAR, '50', null, null, null, null, null, null);
        $table->addFieldInfo('padding', XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null);
        $table->addFieldInfo('innerpadding', XMLDB_TYPE_CHAR, '255', null, null, null, null, null, null);
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));
        $tables[] = $table;

        // globalmessages_rules table
        $table = new XMLDBTable('local_globalmessages_rules');
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('construct', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('leftside', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('operator', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('rightside', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, null, null);
        $table->addFieldInfo('message', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, null, null);
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

        $index = new XMLDBIndex('message');
        $index->setAttributes(XMLDB_INDEX_NOTUNIQUE, array('message'));
        $table->addIndex($index);
        $tables[] = $table;

        return $tables;
    }
}
