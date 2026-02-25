<?php
namespace local_courseanalytics;

defined('MOODLE_INTERNAL') || die();

/**
 * Event observers for analytics tracking.
 */
class observer {
    /**
     * Observe course created event.
     *
     * @param \core\event\course_created $event
     */
    public static function course_created(\core\event\course_created $event): void {
        $courseid = null;
        if (isset($event->objectid)) {
            $courseid = (int) $event->objectid;
        } else if (!empty($event->courseid)) {
            $courseid = (int) $event->courseid;
        }

        $userid = !empty($event->userid) ? (int) $event->userid : null;

        analytics_service::record_event('\\core\\event\\course_created', $courseid, $userid);
    }
}
