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
class moo_globalmessage_form_message extends moo_globalmessage_form
{

    public function definition()
    {
        $this->_form->addElement('hidden', 'id', '', array('id' => 'id_message'));
        $this->_form->addElement('hidden', 'actiontype', 'insert', array('id' => 'id_actiontype'));
        $this->_form->setType('id', PARAM_INT);
        $this->_form->setType('actiontype', PARAM_ALPHA);

        $this->_form->addElement('header', 'general', $this->get_string_fromcore('general'));

        $this->_form->addElement('text', 'name', $this->get_string('globalmessagename'));
        $this->_form->setType('name', PARAM_TEXT);
        $this->_form->addRule('name', null, 'required', null, 'server');

        $this->_form->addElement('textarea', 'summary', $this->get_string_fromcore('summary'));
        $this->_form->setType('summary', PARAM_RAW);

        $this->_form->addElement('htmleditor', 'description', $this->get_string_fromcore('description'));
        $this->_form->setType('description', PARAM_RAW);
        $this->_form->addRule('description', $this->get_string_fromcore('required'), 'required', null, 'server');

        $ynoptions = array(0 => $this->get_string_fromcore('no'), 1 => $this->get_string_fromcore('yes'));
        $this->_form->addElement('select', 'status', $this->get_string('enabled'), $ynoptions);
        $this->_form->setDefault('status', 0);
        $this->_form->setType('status', PARAM_INT);

        $designs = $this->view->designs;
        if (is_array($designs)) {
            $designs = array($this->get_string_fromcore('choose')) + $designs;
        }
        $this->_form->addElement('select', 'design', $this->get_string('design'), $designs);
        $this->_form->setType('design', PARAM_INT);
    }

    public function validation($data, $files)
    {
        $errors = array();

        if ($data['name'] == '' || $data['description'] == '') {
            $errors['name'] = $this->get_string('messageerror2');
        }

        return $errors;
    }
}
