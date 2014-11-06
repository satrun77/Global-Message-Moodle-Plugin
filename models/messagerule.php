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
class moo_globalmessage_model_messagerule extends moo_globalmessage_model
{
    private $leftside = null;
    private $constructs = null;
    private $operators = null;

    const LEFTSIDE_COURSE = 'courseid';
    const LEFTSIDE_USERID = 'userid';
    const LEFTSIDE_DATE = 'date';
    const LEFTSIDE_CODE = 'code';

    const CONSTRUCT_IF = 'if';
    const CONSTRUCT_AND = 'and';
    const CONSTRUCT_OR = 'or';

    public function __construct(moo_globalmessage $base)
    {
        parent::__construct($base);

        $this->register_rules();

        $this->constructs = array(
            self::CONSTRUCT_IF => $this->globalmessage->get_string('if'),
            self::CONSTRUCT_AND => $this->globalmessage->get_string('and'),
            self::CONSTRUCT_OR => $this->globalmessage->get_string('or'),
        );
        $this->operators = array(
            '1' => $this->globalmessage->get_string('equal'),
            '2' => $this->globalmessage->get_string('notequal'),
            '3' => $this->globalmessage->get_string('greaterthan'),
            '4' => $this->globalmessage->get_string('lessthan'),
            '5' => $this->globalmessage->get_string('lessor'),
            '6' => $this->globalmessage->get_string('greateror'),
        );
    }

    protected function register_rules()
    {
        //standard rules
        $this->leftside = array(
            self::LEFTSIDE_COURSE => $this->globalmessage->get_string('courseid'),
            self::LEFTSIDE_USERID => $this->globalmessage->get_string('userid'),
            self::LEFTSIDE_DATE => $this->globalmessage->get_string('date'),
        );
        // add custom rules
        $this->globalmessage->load_file('models/rule/ruleinterface.php');

        $pluginsdir = $this->globalmessage->get_basedir('models/rule');
        $dir = new DirectoryIterator($pluginsdir);
        foreach ($dir as $file) {
            $filename = $file->getFilename();
            if ($file->isFile() && $filename != 'ruleinterface.php' && substr($filename, strrpos($filename, '.') + 1) == 'php') {
                $this->globalmessage->load_file('models/rule/' . $filename);
                $pluginclass = 'moo_globalmessage_model_rule_' . $file->getBasename('.php');
                $class = new $pluginclass();
                if ($class instanceof moo_globalmessage_model_rule_ruleinterface) {
                    $this->leftside['code_' . $class->get_keyname()] = $class;
                }
            }
        }
    }

    public function get_leftsides()
    {
        return $this->leftside;
    }

    public function get_leftside_names($onlyactive = false)
    {
        $return = array();
        foreach ($this->leftside as $name => $rule) {
            if ($rule instanceof moo_globalmessage_model_rule_ruleinterface) {
                if (!$onlyactive || $rule->is_installed()) {
                    $return[$name] = $rule->get_name();
                }
            } else {
                $return[$name] = $rule;
            }
        }
        return $return;
    }

    public function get_operators()
    {
        return $this->operators;
    }

    public function get_constructs()
    {
        return $this->constructs;
    }

    public function get_construct($value)
    {
        return isset($this->constructs[$value]) ? $this->constructs[$value] : '';
    }

    public function get_leftside($value)
    {
        if (!isset($this->leftside[$value])) {
            return '';
        }

        if (!is_object($this->leftside[$value])) {
            return (string) $this->leftside[$value];
        }

        if (!$this->leftside[$value] instanceof moo_globalmessage_model_rule_ruleinterface) {
            return '';
        }
        return $this->leftside[$value]->get_name();
    }

    public function get_operator($value)
    {
        return isset($this->operators[$value]) ? $this->operators[$value] : '';
    }

    public function fetch_rules_bymessage($messageid)
    {
        $records = $this->db->get_records('local_globalmessages_rules', array('message'=> $messageid), 'id ASC');
        if ($records) {
            return $records;
        }
        return false;
    }

    public function save_rules($rules, $messageid)
    {
        // is valid message id
        $message = $this->globalmessage->model('message')->fetch_message_byid($messageid);
        if (!$message) {
            return false;
        }

        // remove existing rules
        $this->db->delete_records('local_globalmessages_rules', array('message'=> $message->id));

        // filter, validate, and then insert the new rules
        foreach ($rules as $key => $rule) {
            $filteredrule = $this->filter_rule_fordb($rule, $key);
            if ($this->is_valid_rule_fordb($filteredrule)) {
                $rulesdata = array(
                    'construct' => $filteredrule[0],
                    'leftside' => $filteredrule[1],
                    'operator' => $filteredrule[2],
                    'rightside' => $filteredrule[3],
                    'message' => $message->id
                );
                $this->db->insert_record('local_globalmessages_rules', (object) $rulesdata);
            }
        }

        // update the modified time of the message
        $updatemessage = new stdClass();
        $updatemessage->id = $message->id;
        $this->globalmessage->model('message')->save_message($updatemessage);

        return true;
    }

    public function is_date($leftside)
    {
        if ($leftside == self::LEFTSIDE_DATE) {
            return true;
        }
        return false;
    }

    public function is_course($leftside)
    {
        if ($leftside == self::LEFTSIDE_COURSE) {
            return true;
        }
        return false;
    }

    public function is_userid($leftside)
    {
        if ($leftside == self::LEFTSIDE_USERID) {
            return true;
        }
        return false;
    }

    public function is_plugincallback($leftside)
    {
        if (substr($leftside, 0, 4) == self::LEFTSIDE_CODE) {
            return true;
        }
        return false;
    }

    protected function is_valid_rule_fordb($rule)
    {
        if (!isset($rule[0]) || !array_key_exists($rule[0], $this->get_constructs())) {
            return false;
        }
        if (!isset($rule[1]) || !array_key_exists($rule[1], $this->get_leftsides())) {
            return false;
        }
        if (!isset($rule[2]) || !array_key_exists($rule[2], $this->get_operators())) {
            return false;
        }
        if (!isset($rule[3])) {
            return false;
        }
        return true;
    }

    protected function is_expired_rules($rules, $message)
    {
        // if there is OR construct in rules, then the expression could be true and don't expire it
        if (in_array(self::CONSTRUCT_OR, $rules)) {
            return false;
        }

        // the message could be expired
        // check the rules if true, then expire the message
        foreach ($rules as $construct => $value) {
            // only for date expression
            if ($this->is_date($value[0])) {
                // if the date rule return false then
                // the message must be disabled (expired message)
                $expression = $this->process_expression(time(), $value[1], $value[2]);
                if (!$expression) {
                    $this->model('message')->disable($message->id);
                    return true;
                }
            }
        }

        return false;
    }

    protected function filter_rule_fordb($role, $key)
    {
        $segments = explode('|', $role);
        // make sure the first part of the rule is if
        if ($key == 0) {
            $segments[0] = self::CONSTRUCT_IF;
        } else if ($segments[0] == self::CONSTRUCT_IF) {
            // if for any reason the second rule start with "if" then change it to and
            $segments[0] = self::CONSTRUCT_IF;
        }
        // format date to timestamp
        if ($this->is_date($segments[1])) {
            // todo use this ... $date = DateTime::createFromFormat('j-M-Y', $segments[3]);
            $segments[3] = strtotime($segments[3]);
        } else {
            $segments[3] = $this->filter_rightside_value($segments[3]);
        }

        $segments[2] = (int) $segments[2];

        return $segments;
    }

    protected function filter_rightside_value($righside)
    {
        // remove special characters that are used for function call from the right side
        $value = rtrim($righside, '();');
        // remove any characters that are used by PHP
        $value = str_replace(array('();', '()', '->'), '', $value);
        // the value can not be empty
        $value = $value == '' ? 'true' : $value;

        return $value;
    }

    public function check_message_rules($message, $options)
    {
        // options is accessible by moo_globalmessage_model_rule_ruleinterface
        $options['message'] = $message;

        // get all of the message rules
        $rules = $this->fetch_rules_bymessage($message->id);
        if (!$rules) {
            return false;
        }

        $expression = '';
        $rulesparts = array();
        foreach ($rules as $rule) {
            $leftside = $this->get_leftside_value($rule->leftside, $options);
            $rightside = $this->get_rightside_value($rule->rightside);
            $construct = $this->get_construct_value($rule->construct);

            $expression .= ' ' . $construct . ' (boolean)' . (int) $this->process_expression($leftside, $rightside, $rule->operator) . ' === true';
            $rulesparts[$rule->construct] = array(
                $rule->leftside,
                $rightside,
                $rule->operator
            );
        }

        // check and update message status
        // only for a message with date rule
        if ($this->is_expired_rules($rulesparts, $message)) {
            return false;
        }

        // yes eval!!! the comparisons are between booleans and the construct variable can only be one of two "AND", "OR"
        // I don't think eval is dangerous here, BUT if you think so, then please let me know with some examples on how it can cause problems
        $status = @eval("\$expression = " . $expression . ";");
        if ($status === false) {
            return false;
        }

        if ($expression) {
            return true;
        }

        return false;
    }

    protected function get_leftside_value($leftside, $options)
    {
        if ($this->is_course($leftside)) {
            return isset($options['course']->id) ? $options['course']->id : false;
        } else if ($this->is_userid($leftside)) {
            return isset($options['user']->username) ? $options['user']->username : false;
        } else if ($this->is_date($leftside)) {
            return isset($options['time']) ? (int) $options['time'] : false;
        } else if ($this->is_plugincallback($leftside)) {
            return $this->get_plugincallback_value($leftside, $options);
        }

        return false;
    }

    protected function get_plugincallback_value($leftside, $options)
    {
        $pluginname = substr($leftside, 5);
        $pluginclass = 'moo_globalmessage_model_rule_' . $pluginname;
        $this->globalmessage->load_file('models/rule/' . $pluginname . '.php');
        $class = new $pluginclass();
        if (class_exists($pluginclass)
                && $class instanceof moo_globalmessage_model_rule_ruleinterface
                && $class->is_installed()) {
            return $class->validate($options);
        }
        return false;
    }

    protected function get_rightside_value($rightside)
    {
        return $this->filter_rightside_value($rightside);
    }

    protected function get_construct_value($construct)
    {
        switch ($construct) {
            case self::CONSTRUCT_AND:
                return '&&';
            case self::CONSTRUCT_OR:
                return '||';
            case self::CONSTRUCT_IF:
            default:
                return '';
        }
    }

    protected function process_expression($leftside, $rightside, $operator)
    {
        if ($operator == 1) {
            if (strcasecmp($leftside, $rightside) == 0) {
                return true;
            }
            return false;
        } else if ($operator == 2) {
            if (strcasecmp($leftside, $rightside) != 0) {
                return true;
            }
            return false;
        } else if ($operator == 3) {
            if (strcasecmp($leftside, $rightside) > 0) {
                return true;
            }
            return false;
        } else if ($operator == 4) {
            if (strcasecmp($leftside, $rightside) < 0) {
                return true;
            }
            return false;
        } else if ($operator == 5) {
            if (strcasecmp($leftside, $rightside) <= 0) {
                return true;
            }
            return false;
        } else if ($operator == 6) {
            if (strcasecmp($leftside, $rightside) >= 0) {
                return true;
            }
            return false;
        }

        return false;
    }

    public function uninstall_customrule($rule)
    {
        if (!isset($this->leftside[$rule])) {
            return false;
        }

        if (!$this->leftside[$rule]->is_installed()) {
            return true;
        }

        // execute uninstall to remove any database changes
        if (!$this->leftside[$rule]->uninstall()) {
            return false;
        }

        return true;
    }

    public function install_customrule($rule)
    {
        if (!isset($this->leftside[$rule])) {
            return false;
        }

        if ($this->leftside[$rule]->is_installed()) {
            return true;
        }

        // execute uninstall to remove any database changes
        return $this->leftside[$rule]->install();
    }
}
