<?php

function xmldb_local_globalmessage_upgrade($oldversion) {
    global $CFG, $DB, $OUTPUT;

    $dbman = $DB->get_manager();

    if ($oldversion < 2011091503) {

        $table = new xmldb_table('globalmessages_designs');
        $table1 = new xmldb_table('local_globalmessages_designs');
        if ($dbman->table_exists($table) && !$dbman->table_exists($table1)) {
            $dbman->rename_table($table, 'local_globalmessages_designs');
        }

        $table = new xmldb_table('globalmessages');
        $table1 = new xmldb_table('local_globalmessages');
        if ($dbman->table_exists($table) && !$dbman->table_exists($table1)) {
            $dbman->rename_table($table, 'local_globalmessages');
        }

        $table = new xmldb_table('globalmessages_rules');
        $table1 = new xmldb_table('local_globalmessages_rules');
        if ($dbman->table_exists($table) && !$dbman->table_exists($table1)) {
            $dbman->rename_table($table, 'local_globalmessages_rules');
        }

        upgrade_plugin_savepoint(true, 2011091503, 'local', 'globalmessage');
    }

    return true;
}