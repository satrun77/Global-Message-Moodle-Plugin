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

<?php echo $this->render_partial('partial/message-form'); ?>
<?php echo $this->render_partial('partial/rule-form'); ?>
<?php echo $this->render_partial('partial/design-form'); ?>