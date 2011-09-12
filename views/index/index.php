<?php defined('GLOBALMESSAGE_VIEW') or die; ?>

<div class="gm-toolbar">
    <button  id="gm-addnew" class="yui-button yui-push-button" type="button">
        <span><?php echo $this->get_string('createglobalmessage'); ?></span>
    </button>
    <button  id="gm-design" class="yui-button yui-push-button" type="button">
        <span><?php echo $this->get_string('managemessagedesign'); ?></span>
    </button>
</div>

<?php
$table = new html_table();
if ($this->messages) {
    foreach($this->messages as $message) {
        $table->data[] = array(
            "<a href='javascript:;' onclick='globalmessage.showeditform(" . $message->id . ");'>" . $this->textformat($message->name) . "</a>",
            $this->textformat($message->summary),
            $this->dateformat($message->modified),
            $this->statusformat($message->status),
            "<a href='javascript:;' onclick='globalmessage.showrulesform(" . $message->id . ")'>" . $this->get_string('editrules') . "</a> | " .
            "<a href='javascript:;' onclick='globalmessage.showeditform(" . $message->id . ");'>" . $this->get_string_fromcore('edit') . "</a> | " .
            "<a href='javascript:;' onclick='globalmessage.removemessage(" . $message->id . ");'>" . $this->get_string_fromcore('remove') . "</a>",
        );
        $table->rowclasses[] = 'message-' . $message->id;
    }
}
$table->head = array(
    $this->get_string('name'),
    $this->get_string('summary'),
    $this->get_string('timemodified'),
    $this->get_string('status'),
    ''
);
$table->width = '100%';
$table->id = 'gm-table';
$table->size =array('40%','20%','20%','5%','15%');

echo $this->print_table($table);
echo $this->print_paging_bar($this->countmessages, $this->page, $this->perpage, "index.php");
?>

<div id="gm-strings">
    <input type="hidden" name="ruleerror1" value="<?php echo $this->get_string('ruleerror1'); ?>"/>
    <input type="hidden" name="failedajax" value="<?php echo $this->get_string('failedajax'); ?>"/>
    <input type="hidden" name="ruleerror2" value="<?php echo $this->get_string('ruleerror2'); ?>"/>
    <input type="hidden" name="ruleerror3" value="<?php echo $this->get_string('ruleerror3'); ?>"/>
    <input type="hidden" name="messageerror1" value="<?php echo $this->get_string('messageerror1'); ?>"/>
    <input type="hidden" name="messageerror2" value="<?php echo $this->get_string('messageerror2'); ?>"/>
    <input type="hidden" name="save" value="<?php echo $this->get_string('save'); ?>"/>
    <input type="hidden" name="submit" value="<?php echo $this->get_string('submit'); ?>"/>
    <input type="hidden" name="designerror1" value="<?php echo $this->get_string('designerror1'); ?>"/>
    <input type="hidden" name="designerror2" value="<?php echo $this->get_string('designerror2'); ?>"/>
    <input type="hidden" name="yes" value="<?php echo $this->get_string_fromcore('yes'); ?>"/>
    <input type="hidden" name="no" value="<?php echo $this->get_string_fromcore('no'); ?>"/>
    <input type="hidden" name="removedesigntext" value="<?php echo $this->get_string('removedesigntext'); ?>"/>
    <input type="hidden" name="removemessagetext" value="<?php echo $this->get_string('removemessagetext'); ?>"/>
    <input type="hidden" name="confirmtitle" value="<?php echo $this->get_string('confirmtitle'); ?>"/>
    <input type="hidden" name="loadingimg" value="<?php echo $this->base_url('assets/img/loading.gif'); ?>"/>
    <input type="hidden" name="loadingtext" value="<?php echo $this->get_string('loadingtext'); ?>"/>
</div>

<?php echo $this->render_partial('partial/message-form'); ?>
<?php echo $this->render_partial('partial/rule-form'); ?>
<?php echo $this->render_partial('partial/design-form'); ?>