<?php

defined('INTERNAL_ACCESS') or die;

class moo_globalmessage_model_rule_welcome implements moo_globalmessage_model_rule_ruleinterface
{

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
    public function validate($options = null)
    {
        // first time user login into your Moodle instance
        if ($options['user']->lastlogin == 0) {
            return 'true';
        }
        return 'false';
    }

    /**
     * Display name
     * 
     * @return string
     */
    public function get_name()
    {
        return 'Welcome to Our Moodle Instance';
    }

    /**
     * unique name of your class
     * moo_globalmessage_model_rule_[key name]
     * 
     * @return string
     */
    public function get_keyname()
    {
        return 'welcome';
    }

    /**
     * Whether or not the rule plugin installed
     * 
     * @return boolean
     */
    public function is_installed()
    {
        global $CFG;
        return isset($CFG->globalmessage_welcomerule);
    }

    /**
     * Install database changes
     * 
     * @return boolean
     */
    public function install()
    {
        // create config value to indicate whether or not this rule installed
        return set_config('globalmessage_welcomerule', 1);
    }

    /**
     * Uninstall database changes
     * 
     * @return boolean
     */
    public function uninstall()
    {
        return unset_config('globalmessage_welcomerule');
    }

}