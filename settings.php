<?php

if (isset($ADMIN)) {
    include_once realpath(dirname(__FILE__)) . '/globalmessage/lib/base.php';
    moo_globalmessage::set_adminsettings($ADMIN);
}
