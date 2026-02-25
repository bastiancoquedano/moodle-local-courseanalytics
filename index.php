<?php
require('../../config.php');

require_login();

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$PAGE->set_url(new moodle_url('/local/courseanalytics/index.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pagetitle', 'local_courseanalytics'));
$PAGE->set_heading(get_string('pageheading', 'local_courseanalytics'));

echo $OUTPUT->header();
$dashboard = new \local_courseanalytics\output\dashboard();
echo $OUTPUT->render($dashboard);
echo $OUTPUT->footer();
