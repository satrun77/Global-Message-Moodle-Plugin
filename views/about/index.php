<?php defined('GLOBALMESSAGE_VIEW') or die; ?>
<div id="gm-about-tab" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#gm-general"><em>General</em></a></li>
        <li><a href="#gm-rule-plugin"><em>Custom Rules</em></a></li>
        <li><a href="#gm-author"><em>Author</em></a></li>
        <li><a href="#gm-uninstall"><em>Un-install</em></a></li>
    </ul>
    <div class="yui-content">
        <div id="gm-general">
            <div id="gm-version">Current Version <?php echo $this->version; ?></div>
            <br class="clearer"/>
            <p>Global message is a plug-in that enables a Moodle administrator to create automatic pop-up
                messages. The messages will appear for the users depending on the rules applied to each message.</p>
            <p>A new message will be displayed once for each user when they log in, then will not be displayed again.</p>

            <strong>List of message examples:</strong>
            <ul>
                <li>Important server outage in the next 1/2 hour.</li>
                <li>Welcome message to new user. First time logged in.</li>
                <li>Holiday Message (e.g. New Year shutdown).</li>
                <li>The lab for the course Introductory to Chemistry has been changed.</li>
                <li>Any other important notices that you would like to show to the users...</li>
            </ul>

            <div id="gm-notice" class="noticebox">
                <p><strong>Note:</strong> Multiple messages can be scheduled to appear on a page, however only one message will be visible. The most recent modified message will be the only message that displays.</p>

				<h3>Example Scenarios:</h3>
                <strong>Assuming there are two messages,</strong><br />
                
                <ul>
                    <li>Message 1 "Welcome to Our Moodle"</li>
                    <li>Message rules:
                        <ul>
                            <li>If the user has never been logged in before, then show this message</li>
                        </ul>
                    </li>
                    <li>Last modified time: Tuesday, 8 March 2010</li>
                </ul>
                <ul>
                    <li>Message 2 "Important system outage for urgent upgrade...."</li>
                    <li>Message rules:
                        <ul>
                            <li>If the current date equal to Tuesday, 19 April 2011</li>
                        </ul>
                    </li>
                    <li>Last modified time: Tuesday, 18 April 2011</li>
                </ul>
                <ul>
                <p>When a new user logs into Moodle for the <strong>first time</strong> and the date is <strong>Tuesday, 19 April 2011</strong>, there are two messages that the user qualifies
                to be visible for (<strong>Message 1</strong> and <strong>Message 2</strong>).</p>
                <p>The most recently modified message is the only one that is going to be visible. In this instance it is <strong>Message 2: "Important system outage for urgent upgrade....".</strong>
				</ul>            
            </div>
        </div>
        <div id="gm-rule-plugin">
            <p>This section will show you step by step how to create a custom rule. You will only need to create a class that implements one interface.</p>
            <ol>
                <li>Create a PHP file and place it under the directory <?php echo $this->get_config('dirroot') . '/local/globalmessage/models/rule/'; ?></li>
                <li>Copy and paste the <a href="#rule-template">template</a> below into your PHP file.</li>
                <li>Replace every <strong>{uniqe_name}</strong> in the template with the name of your PHP file.</li>
                <li>Replace <strong>{Rule Display Text}</strong> with text to describe your class.</li>
                <li>The method <strong>validate()</strong> is where you put the logic of your rule.</li>
            </ol>
            <div id="rule-template">
                <?php highlight_string($this->ruletemplate); ?>
            </div>
        </div>
        <div id="gm-author">
            <ul>
                <li><strong>Name:</strong> Mohamed Alsharaf (mohamed.alsharaf@gmail.com)</li>
                <li><strong>Website</strong> <a href="http://jamandcheese-on-phptoast.com/" target="_blank">http://jamandcheese-on-phptoast.com/</a></li>
                <li><strong>Copyright:</strong> 2011-<?php echo date('Y')+1; ?> Mohamed Alsharaf</li>
                <li><strong>License:</strong> <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">http://www.gnu.org/copyleft/gpl.html</a></li>
            </ul>
        </div>
        <div id="gm-uninstall">
            <p>Steps to un-install the plugin</p>
            <ol>
                <li>remove code from /local/settings.php</li>
                <li>remove code from upgrade.php</li>
                <li>remove code from theme file</li>
                <li>click here to drop all database tables and plugin global settings.</li>
                <li>Optional, remove the folder /local/globalmessage/</li>
            </ol>
        </div>
    </div>
</div>