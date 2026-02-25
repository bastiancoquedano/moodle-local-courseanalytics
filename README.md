# Course Engagement Analytics (local_courseanalytics)

## Overview
Course Engagement Analytics is a Moodle local plugin that captures key learning-platform events and exposes site-level engagement metrics in an administrative dashboard.

This project is designed as a portfolio-grade implementation focused on clean separation of responsibilities (event capture, service layer, persistence, and rendering) and maintainable Moodle plugin architecture.

## Features
- Listens to core Moodle events:
  - `\core\event\course_created`
  - `\core\event\user_enrolment_created`
  - `\core\event\course_completed`
- Persists engagement events in `local_courseanalytics_metrics`.
- Uses a dedicated service layer (`analytics_service`) for write/read aggregation logic.
- Provides an admin dashboard renderable + Mustache UI for key totals:
  - Total courses created
  - Total enrolments
  - Total completions
- Supports localization through language packs (`en`, `es`).

## Installation
1. Place the plugin in:
   - `moodle/local/courseanalytics`
2. From your Moodle root, run plugin upgrade:
   - `php admin/cli/upgrade.php --non-interactive`
3. Ensure your user has site administration capability:
   - `moodle/site:config`
4. Open the dashboard:
   - `/local/courseanalytics/index.php`

### Development (Docker example)
If your Moodle runs in Docker, run upgrade from the web container:
- `docker exec moodle_web php /var/www/html/admin/cli/upgrade.php --non-interactive`

## Architecture
High-level flow:

`Moodle Event -> Observer -> analytics_service -> local_courseanalytics_metrics -> Renderable -> Mustache Dashboard`

Component responsibilities:
- `db/install.xml`: schema definition for the metrics table.
- `db/events.php`: event-to-callback registrations.
- `classes/observer.php`: thin event handlers; extracts IDs and delegates only.
- `classes/analytics_service.php`: persistence and aggregate queries using Moodle `$DB` API.
- `classes/output/dashboard.php`: renderable/templatable context export.
- `templates/dashboard.mustache`: presentation layer (no DB logic).

This architecture keeps domain logic out of UI and observer callbacks, making the plugin easier to test and extend.

## Future Improvements
- Date-range filters and period-over-period comparisons.
- Trend visualizations (daily/weekly/monthly charts).
- Aggregation jobs for large-scale datasets.
- CSV/JSON export endpoints for reporting pipelines.
- Threshold-based alerts for unusual activity patterns.
- Additional engagement signals (e.g., activity completion, quiz attempts, forum interactions).

## Author
Bastian Coquedano  
Moodle Plugin Developer | PHP | LMS Integrations | Advanced Reporting
