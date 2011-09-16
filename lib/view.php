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
define('GLOBALMESSAGE_VIEW', 1);

class moo_globalmessage_view
{
    protected $globalmessage;
    protected $layout = true;
    protected $data = array();
    protected $output;
    
    public function __construct(moo_globalmessage $base)
    {
        global $OUTPUT;

        $this->globalmessage = $base;
        $this->data['version'] = $this->globalmessage->get_version();
        $this->output = $OUTPUT;
    }

    /**
     * Disabled layout
     *
     * @return moo_globalmessage_view
     */
    public function disable_layout()
    {
        $this->layout = false;
        return $this;
    }

    /**
     * Render view
     * 
     * @param string $view
     * @return void
     */
    public function render($view)
    {
        if ($this->layout) {
            echo $this->output->header();
        }

        if ($this->pageheading != '') {
            echo $this->output->heading($this->pageheading, 2);
        }

        include_once $this->globalmessage->get_basedir('views/' . $view . '.php');

        if ($this->layout) {
            echo $this->output->footer();
        }        
    }

    /**
     * Render partial view and turn it's content
     * 
     * @param string $view
     * @param array $data
     * @return string
     */
    public function render_partial($view, array $data = array())
    {
        $this->merge_view_data($data);
        ob_start();
        include $this->globalmessage->get_basedir('views/' . $view . '.php');
        return ob_get_clean();        
    }

    /**
     * Merge new data with the existing one
     * 
     * @param array $data
     * @return void
     */
    protected function merge_view_data($data)
    {
        if (!empty($data) && is_array($data)) {
            $this->data = array_merge($this->data, $data);
        }
    }

    /**
     * Get language string from plugin specific lang dir
     *
     * @param string $name
     * @param string|object $a
     * @return string
     */
    protected function get_string($name, $a= null)
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
    protected function get_string_fromcore($name, $a= null)
    {
        return $this->globalmessage->get_string_fromcore($name, $a);
    }

    /**
     * Returns the name of the current theme
     * 
     * @return string
     */
    protected function get_themename()
    {
        global $PAGE;

        return $PAGE->theme->name;
    }

    /**
     * Get base url of the mod
     *
     * @param string $path
     * @return string
     */
    public function base_url($path = '', $relative = false)
    {
        if ($relative) {
            return '/local/globalmessage/' . $path;
        }
        return $this->get_config()->wwwroot . '/local/globalmessage/' . $path;
    }

    /**
     * Get Moodle base url
     * 
     * @param String $path
     * @return string
     */
    public function core_url($path = '')
    {
        return $this->get_config()->wwwroot . '/' . $path;
    }

    /**
     * Print table
     * 
     * @param array|object $table
     */
    public function print_table($table)
    {
        return html_writer::table($table);
    }

    /**
     * Print pagination bar
     * 
     * @param int $count
     * @param int $page
     * @param int $perpage
     * @param string $url 
     */
    public function print_paging_bar($count, $page = 1, $perpage = 20, $url = '')
    {
        $url .= "?perpage=" . $perpage . "&amp;";
        return $this->output->paging_bar($count, $page, $perpage, $url);
    }

    /**
     * Format text
     * 
     * @param string $text
     * @param int    $type
     * @return string
     */
    public function textformat($text, $type = FORMAT_HTML)
    {
        return format_text($text, $type);
    }

    /**
     * Format date
     * 
     * @param string $date
     * @return string
     */
    public function dateformat($date, $format = '')
    {
        return userdate($date, $format);
    }

    /**
     * Format status from integer to string
     * 
     * @param int $status
     * @return string
     */
    public function statusformat($status)
    {
        if ($status == 1) {
            return $this->get_string('enabled');
        }
        return $this->get_string('disabled');
    }

    /**
     * Return select menu
     * 
     * @param string $name
     * @param array $options
     * @param array $attribs
     * @return string
     */
    protected function form_select($name, array $options, array $attribs = array())
    {
        $defaultoptions = array(
            'nothing' => '',
            'script' => '',
            'nothingvalue' => '',
            'disabled' => false,
            'tabindex' => 0,
            'listbox' => false,
            'multi' => false,
            'class' => '',
            'selected' => ''
        );
        $attribs = array_merge($defaultoptions, $attribs);

        $attributes = array();
        $attributes['disabled'] = $attribs['disabled'];
        $attributes['tabindex'] = $attribs['tabindex'];
        $attributes['multi'] = $attribs['multi'];
        $attributes['class'] = $attribs['class'];
        $attributes['id'] = $name;

        return html_writer::select($options, $name, $attribs['selected'], array($attribs['nothingvalue'] => $attribs['nothing']), $attributes);
    }

    protected function get_user()
    {
        return $this->globalmessage->get_user();
    }

    protected function get_course()
    {
        return $this->globalmessage->get_course();
    }

    protected function get_config($name = null)
    {
        return $this->globalmessage->get_config($name);
    }
    
    /**
     * Helper method for message design
     * Return styles of the outter div
     * 
     * @param object $message
     * @return string 
     */
    protected function message_outter_styles($message)
    {
        $styles = 'width:' . (int) $message->width . 'px;height:' . (int) $message->height . 'px;'
                . 'padding:' . join('px ', $message->padding) . 'px;';

        if ($message->bordersize > 0) {
            $styles .= 'border: ' . $message->bordersize . 'px ' . $message->bordershape . ' ' . $message->bordercolor . ';';
        }

        if ($message->bgcolor != '') {
            $styles .= 'background-color:' . $message->bgcolor . ';';
        }
        if ($message->bgimage != '') {
            $styles .= 'background-image:url(' . $message->bgimage . ');';
        }
        if ($message->bgimageposition != '') {
            $styles .= 'background-position:' . $message->bgimageposition['left'] . 'px ' . $message->bgimageposition['top'] . 'px;';
        }
        if ($message->bgimagerepeat != '') {
            $styles .= 'background-repeat:' . $message->bgimagerepeat . ';';
        }
        $styles .= 'display:block;position:fixed;top:20px;z-index: 90000;left:50%;'
                . 'margin-left:-' . (($message->width + $message->padding['left'] + $message->padding['right']) / 2) . 'px;';

        return $styles;
    }

    /**
     * Helper method for message design
     * Return styles of the inner div
     *
     * @param object $message
     * @return string
     */
    protected function message_inner_styles($message)
    {
        $styles = 'clear:both;padding:' . join('px ', $message->innerpadding) . 'px;';
        return $styles;
    }

    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($key)
    {
        if (isset($this->$key)) {
            return true;
        }
        if (isset($this->data[$key])) {
            return true;
        }
        return false;
    }
}
