<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\\core\\event\\course_created',
        'callback' => '\\local_courseanalytics\\observer::course_created',
    ],
    [
        'eventname' => '\\core\\event\\user_enrolment_created',
        'callback' => '\\local_courseanalytics\\observer::user_enrolment_created',
    ],
    [
        'eventname' => '\\core\\event\\course_completed',
        'callback' => '\\local_courseanalytics\\observer::course_completed',
    ],
];
