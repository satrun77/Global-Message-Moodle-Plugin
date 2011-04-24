<?php defined('GLOBALMESSAGE_VIEW') or die; ?>
<div id="gm-create-message-dialog" class="yui-pe-content gm-dialog">
<div class="hd">
    <span id="create-title"><?php echo $this->get_string('globalmessagedialogcreatetitle'); ?></span>
    <span id="edit-title"><?php echo $this->get_string('globalmessagedialogedittitle'); ?></span>
</div>
<div class="bd">
    <?php $this->form->display(); ?>
</div>
</div>