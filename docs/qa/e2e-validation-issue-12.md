# E2E Validation Report - Issue #12 (Revalidated)

## Environment
- Date (UTC): 2026-02-25 04:33:15 UTC
- Repository: `bastiancoquedano/moodle-local-courseanalytics`
- Branch under validation: `issue-12-qa-e2e-validation`
- Evaluated commit: `8e6982d`
- Current `origin/main`: `458ee5d`
- Moodle runtime: Docker (`moodle_web`, `moodle_db`)
- Plugin version in DB: `2026022502`

## Scope
Validate end-to-end behavior for:
1. Event capture (`course_created`, `user_enrolment_created`, `course_completed`)
2. DB persistence in `local_courseanalytics_metrics`
3. Dashboard metric consistency
4. Access control
5. Upgrade/runtime logs

## Baseline Checks
### CLI upgrade
Command:
```bash
docker exec moodle_web php /var/www/html/admin/cli/upgrade.php --non-interactive
```
Result:
- Completed successfully (no fatal errors).

### Table existence
SQL evidence:
```sql
SELECT COUNT(*) AS table_exists
FROM information_schema.tables
WHERE table_schema='moodle'
  AND table_name='mdl_local_courseanalytics_metrics';
```
Result:
- `table_exists = 1`

## Test Dataset (deterministic)
A controlled CLI script executed the following:
- Cleared `local_courseanalytics_metrics` for deterministic counts.
- Created a new test course.
- Created a new test user.
- Enrolled user (manual enrolment plugin).
- Triggered `\core\event\course_completed`.

Captured identifiers:
- `courseid = 3`
- `testuserid = 4`
- `timestamp = 1771993985`

## Validation Matrix
| Check | Expected | Observed | Status |
|---|---|---|---|
| Upgrade command | Completes without fatal errors | Completed successfully | PASS |
| Metrics table exists | Table present | `mdl_local_courseanalytics_metrics` exists | PASS |
| Event capture/persistence | 3 target events persisted | `course_created=1`, `user_enrolment_created=1`, `course_completed=1` | PASS |
| Service-layer totals | Matches persisted DB totals | Service totals exactly match DB counts | PASS |
| Dashboard consistency | Displayed totals align with data source | Dashboard uses service totals; service=DB parity confirmed | PASS |
| Access control | Admin allowed, non-admin denied | `admin_has_config=true`, `guest_has_config=false` | PASS |
| Runtime logs | No critical plugin errors | No `local_courseanalytics`/`dml_read_exception` entries in sampled logs | PASS |

## Evidence
### DB counts by eventname
```json
{
  "course_created": 1,
  "user_enrolment_created": 1,
  "course_completed": 1
}
```

### Service totals
```json
{
  "course_created": 1,
  "user_enrolment_created": 1,
  "course_completed": 1,
  "counts_match": true
}
```

### Sample persisted rows
```json
[
  {"eventname":"\\core\\event\\course_completed","courseid":"3","userid":"4","timecreated":"1771993985"},
  {"eventname":"\\core\\event\\user_enrolment_created","courseid":"3","userid":"4","timecreated":"1771993985"},
  {"eventname":"\\core\\event\\course_created","courseid":"3","userid":"2","timecreated":"1771993985"}
]
```

### Access control evidence
```json
{
  "admin_has_config": true,
  "guest_has_config": false
}
```

## Conclusion
- Overall result: **PASS**
- Release status: **Merge-ready from QA perspective**

## Notes
- Previous blocker (missing metrics table on existing installations) is resolved by upgrade migration fix in PR #23.
- Revalidation confirms event capture, persistence, and metric aggregation are functioning as expected.
