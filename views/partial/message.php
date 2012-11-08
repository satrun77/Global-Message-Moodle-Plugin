<?php defined('GLOBALMESSAGE_VIEW') or die; ?>

<?php if($this->ispreview): ?>
<div id="gm-message-popup">
    <div>
        <a class="gm-close" style="margin-bottom: 5px;margin-top: 4px;float:right;padding: 3px 6px;background: url(<?php echo $this->base_url('assets/img/close.gif'); ?>) no-repeat scroll 0 0px transparent;cursor: pointer;height: 9px;width: 17px;overflow: hidden;text-decoration: none;text-indent: -999999px;" href="javascript:;">[x]</a>
        <div id="gm-message-inner" style="clear:both;">
            <p>Lorem ipsum dolor sit amet, <a href="">consectetur</a> adipiscing elit. Cras congue blandit suscipit. Nulla lobortis tempus eleifend. Morbi mi massa,
                laoreet nec egestas ac, tincidunt sed massa. Proin eleifend, leo eu feugiat pharetra, erat urna dictum leo, ultrices aliquam magna libero
                vitae ligula.</p>
            <p>Aenean eu laoreet elit. <b>In</b> hac habitasse platea dictumst. Donec lacinia sodales arcu et hendrerit. Morbi sed neque viverra odio
                feugiat placerat. Maecenas eros risus, semper eu hendrerit non, lacinia id lacus. Mauris a ligula dui. Duis molestie nisl ac sem
                gravida ornare. Curabitur ac lobortis risus. Praesent sit amet sem interdum diam tristique sagittis. Vivamus pellentesque condimentum
                turpis vel ultrices. Donec nec libero non mi aliquet semper. Phasellus accumsan interdum magna sed consequat. Phasellus mollis lobortis
                diam vitae egestas. Suspendisse lectus leo, ullamcorper quis malesuada sit amet, lacinia ut nisl. Donec vitae accumsan sem.</p>
        </div>
    </div>
</div>
<?php elseif($this->message): ?>
<div style="<?php echo $this->message_outter_styles($this->message); ?>" id="gm-message-popup">
    <div>
        <a class="gm-close" style="margin-bottom: 5px;margin-top: 4px;float:right;padding: 3px 6px;background: url(<?php echo $this->base_url('assets/img/close.gif'); ?>) no-repeat scroll 0 0px transparent;cursor: pointer;height: 9px;width: 17px;overflow: hidden;text-decoration: none;text-indent: -999999px;" href="javascript:;" onclick="document.getElementById('gm-message-popup').style.display='none';">[x]</a>
        <div id="gm-message-inner" style="<?php echo $this->message_inner_styles($this->message); ?>"><?php echo $this->message->description; ?></div>
    </div>
</div>
<?php endif; ?>