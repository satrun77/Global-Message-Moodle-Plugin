<?php defined('GLOBALMESSAGE_VIEW') or die; ?>
<div id="gm-about-tab" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#gm-general"><em><?php echo $this->get_string('general'); ?></em></a></li>
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
            <ul>
                <li><strong><?php echo $this->get_string_fromcore('name'); ?>:</strong> Mohamed Alsharaf (mohamed.alsharaf@gmail.com)</li>
                <li><strong><?php echo $this->get_string('website'); ?></strong> <a href="http://jamandcheese-on-phptoast.com/" target="_blank">http://jamandcheese-on-phptoast.com/</a></li>
                <li><strong><?php echo $this->get_string('copyright'); ?>:</strong> 2011-<?php echo date('Y')+1; ?> Mohamed Alsharaf</li>
                <li><strong><?php echo $this->get_string('license'); ?>:</strong> <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">http://www.gnu.org/copyleft/gpl.html</a></li>
            </ul>
        </div>
        <div id="gm-uninstall">
            <p><?php echo $this->get_string('uninstall1'); ?></p>
            <ol>
                <li><?php echo $this->get_string('uninstall2'); ?></li>
                <li><?php echo $this->get_string('uninstall3'); ?></li>
                <li><?php echo $this->get_string('uninstall4'); ?></li>
                <li><?php echo $this->get_string('uninstall5'); ?></li>
                <li><?php echo $this->get_string('uninstall6'); ?></li>
            </ol>
        </div>
    </div>
</div>