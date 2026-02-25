# E2E Validation Report - Issue #12

## Environment
- Date (UTC): 2026-02-25 04:23:55 UTC
- Repository: `bastiancoquedano/moodle-local-courseanalytics`
- Branch under validation: `issue-12-qa-e2e-validation`
- Evaluated commit: `8668836`
- Moodle runtime: Docker (`moodle_web`, `moodle_db`)

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
- `No se necesita actualizar ...`

### Table existence
Command:
```sql
SELECT COUNT(*) AS table_exists
FROM information_schema.tables
WHERE table_schema='moodle'
  AND table_name='local_courseanalytics_metrics';
```
Result:
- `table_exists = 0`

## Validation Matrix
| Check | Expected | Observed | Status |
|---|---|---|---|
| Upgrade command | Completes without fatal errors | Completed without fatal errors | PASS |
| Metrics table exists | `local_courseanalytics_metrics` present | Table missing (`table_exists = 0`) | FAIL |
| Service-layer reads | Queries return totals | `dml_read_exception` (table does not exist) | FAIL |
| Event capture/persistence | Rows written for 3 target events | Not executable due missing table | FAIL (blocked) |
| Dashboard consistency vs DB | UI totals match DB counts | Not executable due missing table dependency | FAIL (blocked) |
| Access control | Admin allowed, non-admin denied | `admin_has_config=true`, `guest_has_config=false` | PASS |
| Runtime logs | No critical plugin errors | No direct `local_courseanalytics` errors in sampled recent logs | PASS* |

`*` Runtime log check is informational only; core blocker is schema absence.

## Evidence
### Capability and service behavior
Command executed via Moodle CLI context produced:
```json
{
  "admin_has_config": true,
  "guest_has_config": false,
  "service_query_ok": false,
  "service_error": "Error al leer de la base de datos",
  "service_error_class": "dml_read_exception",
  "service_debug": "Table 'moodle.mdl_local_courseanalytics_metrics' doesn't exist ..."
}
```

### Root-cause indicator
- Service layer is querying `mdl_local_courseanalytics_metrics`.
- Table is absent in this upgraded environment.
- This blocks observer persistence and dashboard totals.

## Conclusion
- Overall result: **FAIL**
- Release status: **Not merge-ready from QA perspective**

## Blocking Issue
Existing Moodle installation path does not create the metrics table introduced in `db/install.xml`.

### Likely cause
`db/install.xml` handles fresh installs, but there is no upgrade step creating the table for pre-existing plugin installations.

### Recommended fix
Implement an upgrade path in `db/upgrade.php` to create `local_courseanalytics_metrics` when missing, then re-run this E2E suite.

## Re-test Plan
After upgrade fix is merged:
1. Run CLI upgrade again.
2. Re-run event trigger flow.
3. Re-check DB rows by `eventname`.
4. Re-validate dashboard totals and non-admin access behavior.
5. Update this report with PASS evidence.
