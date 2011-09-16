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
abstract class moo_globalmessage_form extends moodleform
{
    protected $globalmessage;
    protected $view;

    public function __construct($action = null, $customdata = null, $method = 'post', $target = '', $attributes = null, $editable = true)
    {
        if (is_array($customdata)) {
            $this->set_customdata($customdata);
            $customdata = '';
        }
        return parent::moodleform($action, $customdata, $method, $target, $attributes, $editable);
    }

    /**
     * Set custom data
     * 
     * @param array $data
     * @return moo_globalmessage_form 
     */
    private function set_customdata(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;
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
     * Get language string from moodle core language
     *
     * @param string $name
     * @param string|object $a
     * @return string
     */
    protected function get_string_fromcore($name, $a = null)
    {
        return $this->globalmessage->get_string_fromcore($name, $a);
    }

    /**
     * Return an array of all error messages
     * 
     * @return array
     */
    public function get_errors()
    {
        $errors = array();
        $elements = $this->_form->_elements;
        foreach ($elements as $element) {
            $error = $this->_form->getElementError($element->getName());
            if ($error != '') {
                $errors[$element->getLabel()] = $error;
            }
        }
        return $errors;
    }
}