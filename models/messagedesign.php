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
class moo_globalmessage_model_messagedesign extends moo_globalmessage_model
{

    public function fetch_design_byid($id)
    {
        $design = $this->db->get_record('local_globalmessages_designs', array('id'=> $id));
        if ($design) {
            $design->bgimageposition = unserialize($design->bgimageposition);
            $design->innerpadding = unserialize($design->innerpadding);
            $design->padding = unserialize($design->padding);
            $design->designname = $design->name;
        }
        return $design;
    }

    public function fetch_all_forlist()
    {
        $list = array();

        $designs = $this->db->get_records('local_globalmessages_designs', null, 'name ASC', 'id, name');
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
        $data->name = $data->designname;

        if (isset($data->designid) && $data->designid > 0) {
            $data->id = $data->designid;
            $this->db->update_record('local_globalmessages_designs', (object) $data);
            return $data->id;
        } else {
            return $this->db->insert_record('local_globalmessages_designs', (object) $data, true);
        }
    }

    public function delete($designid)
    {
        return $this->db->delete_records('local_globalmessages_designs', array('id'=> $designid));
    }
}
