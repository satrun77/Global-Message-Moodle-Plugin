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
abstract class moo_globalmessage_model
{
    protected $globalmessage;
    /**
     *
     * @var moodle_database
     */
    protected $db;
   
    public function __construct(moo_globalmessage $base)
    {
        global $DB;
        
        $this->db = $DB;
        $this->globalmessage = $base;
    }

    /**
     * Load a model class
     *
     * @param string $name
     * @return moo_globalmessage_model
     */
    protected function model($name)
    {
        return $this->globalmessage->model($name);
    }

    protected function table_name($name)
    {
        return $this->globalmessage->get_config('prefix') . $name;
    }
}