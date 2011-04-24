<?php defined('GLOBALMESSAGE_VIEW') or die; ?>
<tr class="r<?php echo $this->message->id%2? 1 : 0; ?> message-<?php echo $this->message->id; ?>">
    <td class="cell c0"><a href="javascript:;" onclick="globalmessage.showeditform(<?php echo $this->message->id; ?>);"><?php echo $this->textformat($this->message->name); ?></a></td>
    <td class="cell c1"><?php echo $this->textformat($this->message->summary); ?></td>
    <td class="cell c2"><?php echo $this->dateformat($this->message->created); ?></td>
    <td class="cell c3"><?php echo $this->statusformat($this->message->status); ?></td>
    <td class="cell c3">
        <a href="javascript:;" onclick="globalmessage.showrulesform(<?php echo $this->message->id; ?>);"><?php echo $this->get_string('editrules'); ?></a> |
        <a href="javascript:;" onclick="globalmessage.showeditform(<?php echo $this->message->id; ?>);"><?php echo $this->get_string_fromcore('edit'); ?></a> |
        <a href="javascript:;" onclick="globalmessage.removemessage(<?php echo $this->message->id; ?>);"><?php echo $this->get_string_fromcore('remove'); ?></a>
    </td>
</tr>