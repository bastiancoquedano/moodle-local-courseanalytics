<?php
require('../../config.php');

require_login();

$context = context_system::instance();
require_capability('moodle/site:config', $context);

$PAGE->set_url(new moodle_url('/local/courseanalytics/index.php'));
                $PAGE->set_context($context);
$PAGE->set_title('Course Analytics');
$PAGE->set_heading('Course Analytics');

echo $OUTPUT->header();
        echo $OUTPUT->heading('Plugin funcionando correctamente 🚀');
echo     $OUTPUT->footer();