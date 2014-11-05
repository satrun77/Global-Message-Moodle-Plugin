<?php

defined('INTERNAL_ACCESS') or die;

/**
 *
 * @package    moo
 * @subpackage globalmessage
 * @copyright  2011 Mohamed Alsharaf
 * @author     Mohamed Alsharaf (mohamed.alsharaf@gmail.com)
 * @version    2.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
abstract class moo_globalmessage_controller
{
    protected $globalmessage;
    protected $action = 'index';
    protected $request = null;
    protected $view;
    protected $pagename = '';
    protected $page;
    
    public function __construct($action, $request, moo_globalmessage $base)
    { 
        global $PAGE;
        
        $this->page = $PAGE;
        $this->action = $action;
        $this->request = $request;
        $this->globalmessage = $base;
        $this->globalmessage->load_file('lib/view.php');
        $this->view = new moo_globalmessage_view($this->globalmessage);
    }

    /**
     * Run controller action and render its view
     * 
     * @return void
     */
    public final function run()
    {
        // check if action exists
        $actionname = $this->action . '_action';
        if (!method_exists($this, $actionname)) {
            print_error('invalidpage');
        }

        // setup admin page and check page permission
        admin_externalpage_setup($this->pagename);
        $sitecontext = context_system::instance();
        // You do not have the required permission to access this page.
        if (!has_capability('moodle/site:config', $sitecontext)) {
            print_error('pagepermission');
        }

        // call the action
        $this->{$actionname}();
        // render request view
        $this->view->render($this->request);
    }

    /**
     * Load a file
     * 
     * @param string $file
     * @return mix
     */
    protected function load_file($file)
    {
        return $this->globalmessage->load_file($file);
    }

    /**
     * Load a model class
     *
     * @param string $name
     * @return moo_globalmessage_model
     */
    protected function model($name)
    {
        return $this->globalmessage->model($name);
    }

    /**
     * Load a form class
     * 
     * @param string $name
     * @param array $options
     * @return moo_globalmessage_form
     */
    protected function form($name, $options = array())
    {
        $CFG = $this->get_config();
        require_once ($CFG->libdir . '/formslib.php');
        $this->load_file('lib/form.php');
        $this->load_file('forms/' . $name . '.php');
        $defaultoptions = array(
            'action' => '',
            'method' => 'post',
            'target' => '',
            'attribs' => null,
            'editable' => true
        );
        $options = array_merge($defaultoptions, $options);

        $class = 'moo_globalmessage_form_' . $name;
        return new $class($options['action'], array('globalmessage' => $this->globalmessage, 'view' => $this->view), $options['method'], $options['target'], $options['attribs'], $options['editable']);
    }

    /**
     * Get language string from plugin specific lang dir
     *
     * @param string $name
     * @param string|object $a
     * @return string
     */
    protected function get_string($name, $a = null)
    {
        return $this->globalmessage->get_string($name, $a);
    }

    /**
     * JSON Encode for array or object
     * 
     * @param array|object $data
     * @param boolean $error
     * @param boolean $exist
     * @return string
     */
    public function ajax_return($data, $error = false, $exist = true)
    {
        if (!is_object($data)) {
            $data = (object) $data;
        }
        $data->error = $error ? 1 : 0;
        if ($exist) {
            echo json_encode((array) $data);
            die;
        }
        return json_encode((array) $data);
    }

    /**
     * Check if the current request is ajax
     * 
     * @return boolean
     */
    public function is_ajax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            return true;
        }
        return false;
    }

    /**
     * Add Javascript file to <head>
     * 
     * @param array $scripts
     * @return mix
     */
    protected function yui2_lib(array $scripts)
    {
        return $this->page->requires->js_module($scripts);
    }

    protected function head_module_script($options, $data = array())
    {
        global $PAGE;
        $options['fullpath'] = '/local/globalmessage/assets/js/' . $options['fullpath'];
        return $PAGE->requires->js_init_call('M.moo_gm.init', $data, false, $options);
    }
    protected function head_script(array $scripts)
    {
        foreach ($scripts as $script) {
            $this->page->requires->js($script);
        }
    }
    
    /**
     * Add CSS file to the <head>
     * 
     * @param array $links
     * @return void
     */
    protected function head_link(array $links)
    {
        foreach ($links as $link) {
            $this->page->requires->css($link);
        }
    }

    protected function get_user()
    {
        return $this->globalmessage->get_user();
    }

    protected function get_course()
    {
        return $this->globalmessage->get_course();
    }

    protected function get_config($name = null)
    {
        return $this->globalmessage->get_config($name);
    }
}
