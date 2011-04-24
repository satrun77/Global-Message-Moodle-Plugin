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
class moo_globalmessage_model_messagedesign extends moo_globalmessage_model
{

    public function fetch_design_byid($id)
    {
        $design = get_record('globalmessages_designs', 'id', $id);
        if ($design) {
            $design->bgimageposition = unserialize($design->bgimageposition);
            $design->innerpadding = unserialize($design->innerpadding);
            $design->padding = unserialize($design->padding);
        }
        return $design;
    }

    public function fetch_all_forlist()
    {
        $list = array();

        $designs = get_records('globalmessages_designs', '', '', 'id, name');
        if (!$designs) {
            return $list;
        }

        foreach ($designs as $design) {
            $list[$design->id] = $design->name;
        }

        return $list;
    }

    public function save_design($data)
    {
        $data->innerpadding = serialize($data->innerpadding);
        $data->padding = serialize($data->padding);
        $data->bgimageposition = serialize($data->bgimageposition);

        if (isset($data->designid) && $data->designid > 0) {
            $data->id = $data->designid;
            update_record('globalmessages_designs', (object) $data);
            return $data->id;
        } else {
            return insert_record('globalmessages_designs', (object) $data);
        }
    }

    public function delete($designid)
    {
        return delete_records('globalmessages_designs', 'id', $designid);
    }
}
