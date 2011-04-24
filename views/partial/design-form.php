<?php defined('GLOBALMESSAGE_VIEW') or die; ?>

<div id="gm-design-message-dialog" class="yui-pe-content gm-dialog">
<div class="hd"><?php echo $this->get_string('globalmessagedialogdesigntitle'); ?></div>
<div class="bd">
    <div class="gm-toolbar">
        <label for="gmdesign"><?php echo $this->get_string('loaddesign'); ?>:</label><?php echo $this->form_select('gmdesign_id', $this->designs, array(
            'nothing' => $this->get_string('newdesign')
        )); ?>
        <button id="gm-remove-design"><?php echo $this->get_string('removedesign'); ?></button>
        <button id="gm-preview-design"><?php echo $this->get_string('previewdesign'); ?></button>
        <button id="gm-updatepreview-design"><?php echo $this->get_string('updatepreviewdesign'); ?></button>
    </div>
    <div id="gm-message-design-preview">
        <?php echo $this->render_partial('partial/message', array('ispreview' => true)); ?>
    </div>
    <div id="gm-design-form">
        <?php echo $this->designform->display(); ?>
    </div>
</div>
</div>
