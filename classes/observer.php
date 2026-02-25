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

    /**
     * Observe user enrolment created event.
     *
     * @param \core\event\user_enrolment_created $event
     */
    public static function user_enrolment_created(\core\event\user_enrolment_created $event): void {
        $courseid = !empty($event->courseid) ? (int) $event->courseid : null;

        $userid = null;
        if (!empty($event->relateduserid)) {
            $userid = (int) $event->relateduserid;
        } else if (!empty($event->userid)) {
            $userid = (int) $event->userid;
        }

        analytics_service::record_event('\\core\\event\\user_enrolment_created', $courseid, $userid);
    }

    /**
     * Observe course completed event.
     *
     * @param \core\event\course_completed $event
     */
    public static function course_completed(\core\event\course_completed $event): void {
        $courseid = null;
        if (!empty($event->courseid)) {
            $courseid = (int) $event->courseid;
        } else if (!empty($event->objectid)) {
            $courseid = (int) $event->objectid;
        }

        $userid = null;
        if (!empty($event->relateduserid)) {
            $userid = (int) $event->relateduserid;
        } else if (!empty($event->userid)) {
            $userid = (int) $event->userid;
        }

        analytics_service::record_event('\\core\\event\\course_completed', $courseid, $userid);
    }
}
