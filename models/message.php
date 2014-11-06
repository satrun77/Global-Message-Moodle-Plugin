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
class moo_globalmessage_model_message extends moo_globalmessage_model
{

    public function delete($messageid)
    {
        $this->db->delete_records('local_globalmessages_rules', array('message'=> $messageid));
        return $this->db->delete_records('local_globalmessages', array('id'=> $messageid));
    }

    public function disable($messageid)
    {
        $data = new stdClass();
        $data->id = $messageid;
        $data->status = '0';
        return $this->save_message($data);
    }

    public function count_messages($conditions = null)
    {
        return $this->db->count_records('local_globalmessages', $conditions);
    }

    public function fetch_all_messages($options = null)
    {
        $defaultOptions = array(
            'page' => '',
            'perpage' => '',
        );

        $options = array_merge($defaultOptions, $options);

        if ($options['page'] != '' && $options['perpage'] != '') {
            $options['page'] = $options['page'] * $options['perpage'];
        }

        return $this->db->get_records_sql("SELECT * FROM {local_globalmessages} ORDER BY modified DESC", null, $options['page'], $options['perpage']);
    }

    public function fetch_message_byid($id)
    {
        return $this->db->get_record('local_globalmessages', array('id' => $id));
    }

    public function save_message($data)
    {
        if (isset($data->id) && $data->id > 0) {
            $data->modified = time();
            $this->db->update_record('local_globalmessages', (object) $data);
            return $data->id;
        } else {
            $data->created = time();
            $data->modified = $data->created;
            return $this->db->insert_record('local_globalmessages', (object) $data, true);
        }
    }

    public function get_message_incurrentpage($options)
    {
        $sql = "SELECT g.*, d.height, d.width, d.bgcolor, d.bgimage, d.bgimageposition, d.bgimagerepeat, d.bordersize, d.bordercolor, "
                . "d.innerpadding, d.padding, d.bordershape "
                . "FROM {local_globalmessages} AS g "
                . "LEFT JOIN {local_globalmessages_designs} AS d ON d.id = g.design "
                . "WHERE g.status = 1 "
                . "ORDER BY g.modified DESC";
        // @todo cache the result?
        $messages = $this->db->get_records_sql($sql);
        $messagerule = $this->globalmessage->model('messagerule');
        if (!$messages) {
            return false;
        }
        foreach ($messages as $message) {
            // skip the message if the current user has seen it
            if (isset($options['user']->globalmessage) && in_array($message->id, $options['user']->globalmessage)) {
                continue;
            }
            // check rules if ok return and stop
            if ($messagerule->check_message_rules($message, $options)) {
                // unserialize some styles
                $message->bgimageposition = unserialize($message->bgimageposition);
                $message->innerpadding = unserialize($message->innerpadding);
                $message->padding = unserialize($message->padding);
                // set this message as seen so it will not be shown again to the same user
                $options['user']->globalmessage[] = $message->id;
                return $message;
            }
        }
    }
}
