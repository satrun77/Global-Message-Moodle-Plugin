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
class moo_globalmessage_form_design extends moo_globalmessage_form
{

    public function definition()
    {
        $this->_form->addElement('hidden', 'designid', '', array('id' => 'designid'));
        $this->_form->addElement('hidden', 'actiontype', 'insert', array('id' => 'id_designactiontype'));
        $this->_form->setType('designid', PARAM_INT);
        $this->_form->setType('actiontype', PARAM_ALPHA);

        $this->_form->addElement('header', 'dimensions', $this->get_string('dimensions'));

        $this->_form->addElement('text', 'designname', $this->get_string('name'));
        $this->_form->setType('designname', PARAM_TEXT);
        $this->_form->addRule('designname', null, 'required', null, 'server');

        $this->_form->addElement('text', 'width', $this->get_string('width'));
        $this->_form->setType('width', PARAM_INT);
        $this->_form->addRule('width', null, 'required', null, 'server');
        $this->_form->setDefault('width', 400);

        $this->_form->addElement('text', 'height', $this->get_string('height'));
        $this->_form->setType('height', PARAM_INT);
        $this->_form->addRule('height', null, 'required', null, 'server');
        $this->_form->setDefault('height', 400);

        $padding = array();
        $padding[] = $this->_form->createElement('text', 'top', $this->get_string('top'), array('class' => 'small', 'value' => 0));
        $padding[] = $this->_form->createElement('text', 'right', $this->get_string('right'), array('class' => 'small', 'value' => 0));
        $padding[] = $this->_form->createElement('text', 'bottom', $this->get_string('bottom'), array('class' => 'small', 'value' => 0));
        $padding[] = $this->_form->createElement('text', 'left', $this->get_string('left'), array('class' => 'small', 'value' => 0));
        $this->_form->addGroup($padding, 'padding', $this->get_string('padding'), array(' '), true);
        $this->_form->setType('padding', PARAM_INT);

        $innerpadding = array();
        $innerpadding[] = $this->_form->createElement('text', 'top', $this->get_string('top'), array('class' => 'small', 'value' => 0));
        $innerpadding[] = $this->_form->createElement('text', 'right', $this->get_string('right'), array('class' => 'small', 'value' => 0));
        $innerpadding[] = $this->_form->createElement('text', 'bottom', $this->get_string('bottom'), array('class' => 'small', 'value' => 0));
        $innerpadding[] = $this->_form->createElement('text', 'left', $this->get_string('left'), array('class' => 'small', 'value' => 0));
        $this->_form->addGroup($innerpadding, 'innerpadding', $this->get_string('innerpadding'), array(' '), true);
        $this->_form->setType('innerpadding', PARAM_INT);

        $this->_form->addElement('header', 'background', $this->get_string('background'));

        $this->_form->addElement('text', 'bgcolor', $this->get_string('bgcolor'));
        $this->_form->setType('bgcolor', PARAM_TEXT);

        $this->_form->addElement('text', 'bgimage', $this->get_string('bgimage'));
        $this->_form->setType('bgimage', PARAM_LOCALURL);

        $bgimageposition = array();
        $bgimageposition[] = $this->_form->createElement('text', 'top', $this->get_string('top'), array('class' => 'small', 'value' => 0));
        $bgimageposition[] = $this->_form->createElement('text', 'left', $this->get_string('left'), array('class' => 'small', 'value' => 0));
        $this->_form->addGroup($bgimageposition, 'bgimageposition', $this->get_string('bgimageposition'), array(' '), true);
        $this->_form->setType('bgimageposition', PARAM_INT);

        $repeats = array(
            '' => $this->get_string_fromcore('choose'),
            'repeat' => $this->get_string('bgrepeat'),
            'repeat-x' => $this->get_string('bgrepeatx'),
            'repeat-y' => $this->get_string('bgrepeaty'),
            'no-repeat' => $this->get_string('bgnorepeat'),
        );
        $this->_form->addElement('select', 'bgimagerepeat', $this->get_string('bgimagerepeat'), $repeats);
        $this->_form->setType('bgimagerepeat', PARAM_TEXT);

        $this->_form->disabledif('bgimagerepeat', 'bgimage', 'eq', '');
        $this->_form->disabledif('bgimageposition', 'bgimage', 'eq', '');

        $this->_form->addElement('header', 'border', $this->get_string('border'));

        $this->_form->addElement('text', 'bordersize', $this->get_string('bordersize'));
        $this->_form->setType('bordersize', PARAM_INT);

        $this->_form->addElement('text', 'bordercolor', $this->get_string('bordercolor'));
        $this->_form->setType('bordercolor', PARAM_TEXT);

        $borders = array(
            '' => $this->get_string_fromcore('choose'),
            'solid' => $this->get_string('solid'),
            'dashed' => $this->get_string('dashed'),
            'dotted' => $this->get_string('dotted'),
            'double' => $this->get_string('double'),
            'outset' => $this->get_string('outset'),
            'inset' => $this->get_string('inset'),
            'groove' => $this->get_string('groove'),
            'ridge' => $this->get_string('ridge'),
            'none' => $this->get_string('none'),
        );
        $this->_form->addElement('select', 'bordershape', $this->get_string('bordershape'), $borders);
        $this->_form->setType('bordershape', PARAM_TEXT);
    }

    public function validation($data, $files)
    {
        $errors = array();
        if ($data['designname'] == '') {
            $errors['designname'] = $this->get_string('designerror2');
        }

        if ($data['width'] <= 0 || !is_numeric($data['width'])) {
            $errors['width'] = $this->get_string('designerror1');
        }
        if ($data['height'] <= 0 || !is_numeric($data['height'])) {
            $errors['height'] = $this->get_string('designerror1');
        }
        return $errors;
    }

    public function set_data($default_values, $slashed = false)
    {
        if (!empty($default_values)) {
            if (is_array($default_values)) {
                $default_values['bgimageposition'] = unserialize($default_values['bgimageposition']);
                $default_values['padding'] = unserialize($default_values['padding']);
                $default_values['innerpadding'] = unserialize($default_values['innerpadding']);
            } else if (is_object($default_values)) {
                $default_values->bgimageposition = unserialize($default_values->bgimageposition);
                $default_values->padding = unserialize($default_values->padding);
                $default_values->innerpadding = unserialize($default_values->innerpadding);
            }
        }
        $default_values['designid'] = $default_values['id'];
        return parent::set_data($default_values, $slashed);
    }

}