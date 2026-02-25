<?php
namespace local_courseanalytics;

defined('MOODLE_INTERNAL') || die();

/**
 * Service layer for course analytics persistence.
 */
class analytics_service {
    /**
     * Persist a metric event entry.
     *
     * @param string $eventname Event class name.
     * @param int|null $courseid Related course id.
     * @param int|null $userid Related user id.
     */
    public static function record_event(string $eventname, ?int $courseid = null, ?int $userid = null): void {
        global $DB;

        $record = new \stdClass();
        $record->eventname = $eventname;
        $record->courseid = $courseid;
        $record->userid = $userid;
        $record->timecreated = time();

        $DB->insert_record('local_courseanalytics_metrics', $record);
    }
}
