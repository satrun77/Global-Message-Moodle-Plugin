<?php defined('GLOBALMESSAGE_VIEW') or die; ?>

<div id="gm-rules-dialog" class="yui-pe-content gm-dialog">
    <div class="hd" id="gm-rules-dialog-title"><?php echo $this->get_string('globalmessagedialogrulestitle'); ?></div>
    <div class="bd">
        <form action="<?php echo $this->base_url('index.php?action=index/saverules'); ?>" method="POST" id="gm-rules-form">
            <input type="hidden" name="messageid" id="gm-messageid"/>
            <p class="notes"><?php echo $this->get_string('globalmessagedialogrulesnotes'); ?></p>
            <?php
            $table = new html_table();
            $table->head = array(
                '',
                $this->form_select('rules-state', $this->rule_statments),
                $this->form_select('rules-left', $this->rule_leftsides),
                $this->form_select('rules-operator', $this->rule_operators),
                '<input type="text" name="rules-input" id="rules-input" class="text small"/><div id="gm-calendar"></div>',
                '<a href="javascript:;" id="gm-add-rule">' . $this->get_string_fromcore('add') . '</a>'
            );
            $table->data = '';
            $table->id = 'gm-rulestable';
            $table->width = '100%';
            $table->rowclasses = 'rule-row';
            $table->size = array('2%', '8%', '27%', '23%', '40%', '8%');
            echo $this->print_table($table);
            ?>
        </form>
    </div>
</div>