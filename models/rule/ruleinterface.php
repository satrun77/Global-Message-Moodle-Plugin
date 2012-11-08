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
interface moo_globalmessage_model_rule_ruleinterface
{

    /**
     * Rule logic
     * 
     * @param array $options
     *               <code>
     *               $options = array(
     *                              'user'   => $USER,
     *                              'course' => $COURSE,
     *                              'time'   => // current time used in comparison,
     *                              'message'=> // global message object,);
     *               </code>
     * @return string 'true' or 'false'
     */
    public function validate($options = null);

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

     /**
     * Whether or not the rule plugin installed
      * 
     * @return boolean
     */
    public function is_installed();

    /**
     * Install database changes
     * 
     * @return boolean
      */
     public function install();
 
     /**
     * Uninstall database changes
      * 
     * @return boolean
      */
     public function uninstall();
}
