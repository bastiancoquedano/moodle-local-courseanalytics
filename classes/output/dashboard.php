<?php
namespace local_courseanalytics\output;

defined('MOODLE_INTERNAL') || die();

use local_courseanalytics\analytics_service;
use renderer_base;

/**
 * Renderable dashboard context for course analytics.
 */
class dashboard implements \renderable, \templatable {
    /**
     * Export template context data.
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        return [
            'totalcoursescreated' => analytics_service::get_total_courses_created(),
            'totalenrolments' => analytics_service::get_total_enrolments(),
            'totalcompletions' => analytics_service::get_total_completions(),
        ];
    }
}
