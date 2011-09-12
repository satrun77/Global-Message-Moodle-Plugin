<?php

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('lib/base.php');

$globalmessage = new moo_globalmessage(array(
    'config' => $CFG,
    'user' => $USER,
    'course' => $COURSE
));
$globalmessage->controller(optional_param('action', 'index', PARAM_SAFEPATH))
              ->run();