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
class moo_globalmessage_controller_index extends moo_globalmessage_controller
{

    protected $pagename = 'globalmessagemanage';

    public function index_action()
    {
        $this->view->pageheading = $this->get_string('manageglobalmessageheading');

        $this->head_link(array(
            $this->view->base_url('assets/css/styles.css', true)
        ));
        $this->head_module_script(array(
            'name' => 'moo_gm',
            'fullpath' => 'manage.js',
            'requires' => array('base', 'cssfonts', 'yui2-json', 'node', 'overlay', 'event', 'io', 'yui2-button', 'yui2-calendar', 'yui2-container', 'yui2-connection', 'yui2-animation', 'yui2-element')
        ), array(array(
            'ruleerror1'        => $this->get_string('ruleerror1'),
            'failedajax'        => $this->get_string('failedajax'),
            'ruleerror2'        => $this->get_string('ruleerror2'),
            'ruleerror3'        => $this->get_string('ruleerror3'),
            'messageerror1'     => $this->get_string('messageerror1'),
            'messageerror2'     => $this->get_string('messageerror2'),
            'savemessage'       => $this->get_string('savemessage'),
            'savedesign'        => $this->get_string('savedesign'),
            'saverules'         => $this->get_string('saverules'),
            'designerror1'      => $this->get_string('designerror1'),
            'designerror2'      => $this->get_string('designerror2'),
            'yes'               => get_string('yes'),
            'no'                => get_string('no'),
            'removedesigntext'  => $this->get_string('removedesigntext'),
            'removemessagetext' => $this->get_string('removemessagetext'),
            'confirmtitle'      => $this->get_string('confirmtitle'),
            'loadingimg'        => $this->view->base_url('assets/img/loading.gif'),
            'loadingtext'       => $this->get_string('loadingtext'),
        )));
        $this->head_script(array(
            $this->view->base_url('assets/js/base.js', true)
        ));

        $page = optional_param('page', 0, PARAM_INT);
        $perpage = optional_param('perpage', 30, PARAM_INT);
        $messages = array();

        $countmessages = $this->model('message')->count_messages();
        if ($countmessages > 0) {
            $messages = $this->model('message')->fetch_all_messages(array(
                        'page' => $page,
                        'perpage' => $perpage,
                    ));
        }

        $this->view->designs = $this->model('messagedesign')->fetch_all_forlist();
        $this->view->messages = $messages;
        $this->view->countmessages = $countmessages;
        $this->view->page = $page;
        $this->view->perpage = $perpage;
        $this->view->form = $this->form('message', array(
                    'action' => 'index.php?action=index/editmessage'
                ));

        $rule = $this->model('messagerule');
        $this->view->rule_statments = $rule->get_constructs();
        $this->view->rule_leftsides = $rule->get_leftside_names(true);
        $this->view->rule_operators = $rule->get_operators();

        $this->view->designform = $this->form('design', array(
                    'action' => 'index.php?action=index/editdesign'
                ));
    }

    public function editmessage_action()
    {
        if (!$this->is_ajax()) {
            die;
        }

        try {
            $action = optional_param('actiontype', 'update', PARAM_ALPHA);

            $this->view->designs = $this->model('messagedesign')->fetch_all_forlist();
            $form = $this->form('message', array(
                        'action' => 'index.php?action=index/editmessage'
                    ));

            // form post
            if (!confirm_sesskey()) {
                $this->ajax_return(array('message' => $this->get_string('error1')), true);
            } else if (!$form->is_validated()) {
                $this->ajax_return(array('message' => $this->array_to_errormessage($form->get_errors())), true);
            }

            $formdata = $form->get_data();

            $id = $this->model('message')->save_message($formdata);
            $message = $this->model('message')->fetch_message_byid($id);
            if (!$message) {
                $this->ajax_return(array('message' => $this->get_string('error4')), true);
            }

            $this->ajax_return(array(
                'rowcontent' => $this->view->render_partial('partial/message-row', array('message' => $message)),
                'id' => ($action == 'update' ? $formdata->id : ''),
                'data' => array(
                    $message->name,
                    $message->summary,
                    $this->view->statusformat($message->status)
                )
            ));
        } catch (moodle_exception $e) {
            $this->ajax_return(array('message' => $e->getMessage()), true);
        }
    }

    public function getmessage_action()
    {
        if (!$this->is_ajax()) {
            die;
        }

        $jsondata = array();
        $id = optional_param('id', '-1', PARAM_INT);
        if (-1 !== $id) {
            $jsondata = $this->model('message')->fetch_message_byid($id);
            $this->ajax_return($jsondata);
        }

        $this->ajax_return($jsondata, true);
    }

    public function editrules_action()
    {
        if (!$this->is_ajax()) {
            die;
        }
        $jsondata = array();
        $id = optional_param('id', '-1', PARAM_INT);
        if (-1 !== $id) {
            $jsondata = $this->model('message')->fetch_message_byid($id);

            $rulemodel = $this->model('messagerule');
            $rules = $rulemodel->fetch_rules_bymessage($id);
            $jsondata->rules = array();
            $jsondata->title = $this->get_string('editrulestitle', $jsondata->name);
            $jsondata->constructoptions = $this->constructs_as_options($rulemodel->get_constructs());
            if ($rules) {
                foreach ($rules as $ruleid => $rule) {
                    $jsondata->rules[$ruleid] = (array) $rule;
                    $jsondata->rules[$ruleid]['id'] = $ruleid;
                    $jsondata->rules[$ruleid]['constructtext'] = $rulemodel->get_construct($rule->construct);
                    $jsondata->rules[$ruleid]['leftsidetext'] = $rulemodel->get_leftside($rule->leftside);
                    $jsondata->rules[$ruleid]['operatortext'] = $rulemodel->get_operator($rule->operator);
                    $jsondata->rules[$ruleid]['rightsidetext'] = $rule->rightside;
                    if ($rulemodel->is_date($rule->leftside)) {
                        $jsondata->rules[$ruleid]['rightsidetext'] = $this->view->dateformat($rule->rightside, '%A, %d %B %Y');
                    }
                }
            }
            $this->ajax_return($jsondata);
        }
        $this->ajax_return($jsondata, true);
    }

    private function constructs_as_options($constructs)
    {
        $options = '';
        foreach ($constructs as $value => $label) {
            $options .= '<option value="' . $value . '">' . $label . '</option>';
        }
        return $options;
    }

    public function saverules_action()
    {
        if (!$this->is_ajax()) {
            die;
        }
        $jsondata = array('error' => 0);
        $rules = optional_param_array('gmrules', '-1', PARAM_TEXT);
        $messageid = optional_param('messageid', '-1', PARAM_INT);
        if (is_array($rules) && !empty($rules) && $messageid > 0) {
            $jsondata['message'] = $this->get_string('rulessaved');
            if (!$this->model('messagerule')->save_rules($rules, $messageid)) {
                $jsondata['error'] = '1';
                $jsondata['message'] = $this->get_string('invalidmessageid');
            }
        }
        $this->ajax_return($jsondata);
    }

    public function getdesign_action()
    {
        if (!$this->is_ajax()) {
            die;
        }

        $id = optional_param('id', '', PARAM_INT);
        if ('' !== $id) {
            $formdata = $this->model('messagedesign')->fetch_design_byid($id);
            $this->ajax_return(array(
                'formcontent' => $formdata
            ));
        }
    }

    public function editdesign_action()
    {
        if (!$this->is_ajax()) {
            die;
        }

        $action = optional_param('actiontype', 'update', PARAM_ALPHA);
        $designid = optional_param('designid', '', PARAM_INT);

        // form post
        $form = $this->form('design', array(
                    'action' => 'index.php?action=index/editdesign'
                ));

        if (!confirm_sesskey()) {
            $this->ajax_return(array('message' => $this->get_string('error1')), true);
        } else if (!$form->is_validated()) {
            $this->ajax_return(array('message' => $this->array_to_errormessage($form->get_errors())), true);
        }

        $formdata = $form->get_data();

        $designmodel = $this->model('messagedesign');
        $id = $designmodel->save_design($formdata);
        $design = $designmodel->fetch_design_byid($id);
        if (!$design) {
            $this->ajax_return(array('message' => $this->get_string('error3'), 'ids' => $id), true);
        }

        $this->ajax_return(array(
            'id' => $design->id,
            'name' => $design->name,
            'message' => $this->get_string('designsaved'),
            'type' => $action
        ));
    }

    public function removemessage_action()
    {
        $id = optional_param('id', '-1', PARAM_INT);
        if ($id < 0) {
            die;
        }

        $model = $this->model('message');
        $message = $model->fetch_message_byid($id);
        if (!$message) {
            $this->ajax_return(array('message' => $this->get_string('error2')), true);
        }
        $model->delete($message->id);

        $message->message = $this->get_string('messagedeleted', $message->name);
        $this->ajax_return($message);
    }

    public function removedesign_action()
    {
        $id = optional_param('id', '-1', PARAM_INT);
        if ($id < 0) {
            die;
        }

        $model = $this->model('messagedesign');
        $design = $model->fetch_design_byid($id);
        if (!$design) {
            $this->ajax_return(array('message' => $this->get_string('error4')), true);
        }
        $model->delete($design->id);

        $design->message = $this->get_string('designdeleted', $design->name);
        $this->ajax_return($design);
    }

    protected function array_to_errormessage($errors)
    {
        $message = '';
        foreach ($errors as $element => $error) {
            $message .= $element . ': ' . $error . "\n";
        }
        return $message;
    }
}
