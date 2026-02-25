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

    /**
     * Get total number of created courses events.
     *
     * @return int
     */
    public static function get_total_courses_created(): int {
        global $DB;

        return (int) $DB->count_records('local_courseanalytics_metrics', [
            'eventname' => '\\core\\event\\course_created',
        ]);
    }

    /**
     * Get total number of enrolment created events.
     *
     * @return int
     */
    public static function get_total_enrolments(): int {
        global $DB;

        return (int) $DB->count_records('local_courseanalytics_metrics', [
            'eventname' => '\\core\\event\\user_enrolment_created',
        ]);
    }

    /**
     * Get total number of completion events.
     *
     * @return int
     */
    public static function get_total_completions(): int {
        global $DB;

        return (int) $DB->count_records('local_courseanalytics_metrics', [
            'eventname' => '\\core\\event\\course_completed',
        ]);
    }
}
