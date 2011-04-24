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
interface moo_globalmessage_model_rule_ruleinterface
{

    /**
     * Rule logic
     * 
     * @return string 'true' or 'false'
     */
    public function validate();

    /**
     * Display name
     * 
     * @return string
     */
    public function get_name();

    /**
     * unique name of your class
     * moo_globalmessage_model_rule_[key name]
     * 
     * @return string
     */
    public function get_keyname();
}
