<?php

function xmldb_local_upgrade($oldversion) {

    global $CFG, $db;
    $result = true;

    if ($result && $oldversion < 2011032200) {
        include_once $CFG->dirroot . '/local/globalmessage/lib/base.php';
        $result = moo_globalmessage::install($db);
    }

    return $result;
}