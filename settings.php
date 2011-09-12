<?php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig && isset($ADMIN)) {
    include_once realpath(dirname(__FILE__)) . '/lib/base.php';
    moo_globalmessage::set_adminsettings($ADMIN);
//    $ADMIN->add('root', new admin_externalpage('qeupgradehelper',
//            get_string('pluginname', 'local_qeupgradehelper'),
//            new moodle_url('/local/qeupgradehelper/index.php')));
}