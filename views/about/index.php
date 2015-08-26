<?php defined('GLOBALMESSAGE_VIEW') or die; ?>

<div id="gm-about-tab" class="yui-navset">
    <ul class="yui-nav">
        <li><a href="#gm-general"><em><?php echo $this->get_string('general'); ?></em></a></li>
        <li><a href="#gm-manage-plugin"><em><?php echo $this->get_string('managerules'); ?></em></a></li>
        <li><a href="#gm-rule-plugin"><em><?php echo $this->get_string('customrules'); ?></em></a></li>
        <li><a href="#gm-author"><em><?php echo $this->get_string('author'); ?></em></a></li>
        <li><a href="#gm-uninstall"><em><?php echo $this->get_string('uninstall'); ?></em></a></li>
    </ul>
    <div class="yui-content">
        <div id="gm-general">
            <div id="gm-version"><?php echo $this->get_string('currentversion'); ?> <?php echo $this->version; ?></div>
            <br class="clearer"/>
            <p><?php echo $this->get_string('globalmessageintro1'); ?></p>
            <p><?php echo $this->get_string('globalmessageintro2'); ?></p>

            <strong><?php echo $this->get_string('listofexamples'); ?></strong>
            <ul>
                <li><?php echo $this->get_string('example1'); ?></li>
                <li><?php echo $this->get_string('example2'); ?></li>
                <li><?php echo $this->get_string('example3'); ?></li>
                <li><?php echo $this->get_string('example4'); ?></li>
                <li><?php echo $this->get_string('example5'); ?></li>
            </ul>

            <div id="gm-notice" class="noticebox">
                <p><strong><?php echo $this->get_string('note'); ?>:</strong> <?php echo $this->get_string('notedetails'); ?></p>

                <h3><?php echo $this->get_string('examplescenarios'); ?>:</h3>
                <strong><?php echo $this->get_string('examplescenarios1'); ?></strong><br />

                <ul>
                    <li><?php echo $this->get_string('examplescenarios2'); ?></li>
                    <li><?php echo $this->get_string('examplescenarios3'); ?>
                        <ul>
                            <li><?php echo $this->get_string('examplescenarios4'); ?></li>
                        </ul>
                    </li>
                    <li><?php echo $this->get_string('examplescenarios5'); ?></li>
                </ul>
                <ul>
                    <li><?php echo $this->get_string('examplescenarios6'); ?></li>
                    <li><?php echo $this->get_string('examplescenarios7'); ?>
                        <ul>
                            <li><?php echo $this->get_string('examplescenarios8'); ?></li>
                        </ul>
                    </li>
                    <li><?php echo $this->get_string('examplescenarios9'); ?></li>
                </ul>
                <ul>
                    <p><?php echo $this->get_string('examplescenarios10'); ?></p>
                    <p><?php echo $this->get_string('examplescenarios11'); ?></p>
                </ul>
            </div>
        </div>
        <div id="gm-manage-plugin">
            <?php
            $table = new html_table();
            if ($this->rules) {
                foreach ($this->rules as $rulekey => $rule) {
                    $name = $rule;
                    $installstatus = '';
                    if (strpos($rulekey, 'code_') === 0) {

                        $name = $rule->get_name();
                        $installstatus = "<a href='javascript:;' onclick='globalmessage.installcustomrule(\"" . $rulekey . "\");'>" . $this->get_string('install') . "</a> ";
                        if ($rule->is_installed()) {
                            $installstatus = "<a href='javascript:;' onclick='globalmessage.removecustomrule(\"" . $rulekey . "\");'>" . $this->get_string('uninstall') . "</a> ";
                        }
                    }
                    $table->data[] = array($rulekey, $name, $installstatus);
                    $table->rowclasses[] = 'customrule-' . $rulekey;
                }
            }
            $table->head = array(
                '#',
                $this->get_string('name'),
                $this->get_string('globalmessageinstall'),
            );
            $table->width = '100%';
            $table->id = 'gm-table';
            $table->size = array('10%', '40%', '5%');

            echo $this->print_table($table);
            ?>
        </div>
        <div id="gm-rule-plugin">
            <p><?php echo $this->get_string('rulesinfo1'); ?></p>
            <ol>
                <li><?php echo $this->get_string('rulesinfo2'); ?> <?php echo $this->get_config('dirroot') . '/local/globalmessage/models/rule/'; ?></li>
                <li><?php echo $this->get_string('rulesinfo3'); ?></li>
                <li><?php echo $this->get_string('rulesinfo4'); ?></li>
                <li><?php echo $this->get_string('rulesinfo5'); ?></li>
                <li><?php echo $this->get_string('rulesinfo6'); ?></li>
            </ol>
            <div id="rule-template">
                <?php highlight_string($this->ruletemplate); ?>
            </div>
        </div>
        <div id="gm-author">
            <div style="float:right; border: 1px solid #C4C7D2; padding: 5px;text-align: center; background: none repeat scroll 0 0 #E4EEFF;">
                <h4>Thank you for your support</h4>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
                    <input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>
            <ul>
                <li><strong><?php echo $this->get_string_fromcore('name'); ?>:</strong> Mohamed Alsharaf (mohamed.alsharaf@gmail.com)</li>
                <li><strong><?php echo $this->get_string('website'); ?></strong> <a href="http://my.geek.nz/" target="_blank">http://my.geek.nz/</a></li>
                <li><strong><?php echo $this->get_string('copyright'); ?>:</strong> 2011-<?php echo date('Y') + 1; ?> Mohamed Alsharaf</li>
                <li><strong><?php echo $this->get_string('license'); ?>:</strong> <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">http://www.gnu.org/copyleft/gpl.html</a></li>
            </ul>
        </div>
        <div id="gm-uninstall">
            <p><?php echo $this->get_string('uninstall1'); ?></p>
            <ol>
                <li><?php echo $this->get_string('uninstall0'); ?></li>
                <li><?php echo $this->get_string('uninstall2'); ?></li>
                <li><?php echo $this->get_string('uninstall3'); ?><?php echo highlight_string("include_once \$CFG->dirroot . '/local/globalmessage/lib/base.php';
moo_globalmessage::show_message();
", true); ?></li>
            </ol>
        </div>
    </div>
</div>
