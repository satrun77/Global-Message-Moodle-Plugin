<?php

defined('INTERNAL_ACCESS') or die;

/**
 *
 * @package    moo
 * @subpackage globalmessage
 * @copyright  2011 Mohamed Alsharaf
 * @author     Mohamed Alsharaf (mohamed.alsharaf@gmail.com)
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html
 */
class moo_globalmessage_controller_about extends moo_globalmessage_controller
{
    protected $pagename = 'globalmessageabout';

    public function index_action()
    {
        $this->view->pageheading = $this->get_string('aboutglobalmessageheading');

        $this->yui_skin(array('tabview'));
        $this->head_link(array(
            $this->view->base_url('assets/css/about.css')
        ));
        $this->head_script(array('yui_dom-event', 'yui_element', 'yui_tabview', $this->view->base_url('assets/js/about.js')));

        $this->view->ruletemplate = $this->get_rule_template();
    }

    protected function get_rule_template()
    {
        return "<?php\n
defined('INTERNAL_ACCESS') or die;

class moo_globalmessage_model_rule_{uniqe_name} implements moo_globalmessage_model_rule_ruleinterface {

    public function validate()
    {
       // valdation logic here...
       // return string 'true' or 'false'
       return 'true';
    }

    public function get_name()
    {
        return '{Rule Display Text}';
    }

    public function get_keyname()
    {
        return '{uniqe_name}';
    }
}";
    }
}