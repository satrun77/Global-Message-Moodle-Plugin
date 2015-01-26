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
class moo_globalmessage_controller_about extends moo_globalmessage_controller
{
    protected $pagename = 'globalmessageabout';

    public function index_action()
    {
        $this->view->pageheading = $this->get_string('aboutglobalmessageheading');

        $this->head_link(array(
            $this->view->base_url('assets/css/about.css', true)
        ));

        $this->head_module_script(array(
            'name' => 'moo_gm',
            'fullpath' => 'about.js',
            'requires' => array('base', 'cssfonts', 'tabview', 'yui2-json', 'node', 'overlay', 'event', 'io', 'yui2-button', 'yui2-container', 'yui2-connection', 'yui2-animation', 'yui2-element')
        ), array(array(
            'save'                 => $this->get_string('save'),
            'submit'               => $this->get_string('submit'),
            'yes'                  => get_string('yes'),
            'no'                   => get_string('no'),
            'loadingimg'           => $this->view->base_url('assets/img/loading.gif'),
            'loadingtext'          => $this->get_string('loadingtext'),
            'removecustomruletext' => $this->get_string('removecustomruletext'),
            'confirmtitle'         => $this->get_string('confirmtitle'),
            'install'              => $this->get_string('install'),
            'uninstall'            => $this->get_string('uninstall'),
        )));
        $this->head_script(array(
            $this->view->base_url('assets/js/base.js', true)
        ));
        
        $this->view->ruletemplate = $this->get_rule_template();

        $this->view->rules = $this->model('messagerule')->get_leftsides();
    }

    public function removecustomrule_action()
    {
        if (!$this->is_ajax()) {
            die;
        }

        $rule = optional_param('rule', '', PARAM_TEXT);
        if ($rule == '') {
            die;
        }

        $uninstall = $this->model('messagerule')->uninstall_customrule($rule);
        if (!$uninstall) {
            return $this->ajax_return(array('message' => $this->get_string('customruleerror1', $rule)), true);
        }

        $this->ajax_return(array('message' => $this->get_string('customruledeleted', $rule)));
    }

    public function installcustomrule_action()
    {
        if (!$this->is_ajax()) {
            die;
        }

        $rule = optional_param('rule', '', PARAM_TEXT);
        if ($rule == '') {
            die;
        }

        $uninstall = $this->model('messagerule')->install_customrule($rule);
        if (!$uninstall) {
            return $this->ajax_return(array('message' => $this->get_string('customruleerror2', $rule)), true);
        }

        $this->ajax_return(array('message' => $this->get_string('customruleinstalled', $rule)));
    }

    protected function get_rule_template()
    {
        return "<?php\n
defined('INTERNAL_ACCESS') or die;

class moo_globalmessage_model_rule_{uniqe_name} implements moo_globalmessage_model_rule_ruleinterface {

    /**
     * Rule logic
     * 
     * @param array \$options
     *               <code>
     *               \$options = array(
     *                              'user'   => \$USER,
     *                              'course' => \$COURSE,
     *                              'time'   => // current time used in comparison,
     *                              'message'=> // global message object,);
     *               </code>
     * @return string 'true' or 'false'
     */
    public function validate(\$options = null)
    {
       // valdation logic here...
       // return string 'true' or 'false'
       return 'true';
    }

    /**
     * Display name
     * 
     * @return string
     */
    public function get_name()
    {
        return '{Rule Display Text}';
    }

    /**
     * unique name of your class
     * moo_globalmessage_model_rule_[key name]
     * 
     * @return string
     */
    public function get_keyname()
    {
        return '{uniqe_name}';
    }

     /**
     * Whether or not the rule plugin installed
      * 
     * @return boolean
     */
    public function is_installed()
    {
    
    }

    /**
     * Install database changes
     * 
     * @return boolean
      */
     public function install()
     {
     
     }
 
     /**
     * Uninstall database changes
      * 
     * @return boolean
      */
     public function uninstall()
     {
     
     }
}";
    }
}