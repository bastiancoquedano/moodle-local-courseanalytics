<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_courseanalytics',
        get_string('pluginname', 'local_courseanalytics'),
        new moodle_url('/local/courseanalytics/index.php')
    ));
}
