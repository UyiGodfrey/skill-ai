# SkillBridge AI Model Import Notes

The PHP UI currently uses a simple rule-based match:

- learner skills from `student.current_skills`
- role requirements from `job_roles.required_skills`
- missing skills are calculated at render time

When your Python model is trained, import its output into `model_predictions`.

Expected columns:

- `Student_id`: learner ID from `student.Student_id`
- `role_id`: target role from `job_roles.role_id`
- `model_name`: model/version label, for example `skillgap-xgb-v1`
- `match_score`: percentage score from `0` to `100`
- `payload`: optional JSON with explanations, missing skills, recommended courses, or raw features

Example SQL shape:

```sql
INSERT INTO model_predictions
  (Student_id, role_id, model_name, match_score, payload)
VALUES
  ('LRN-001', 1, 'skillgap-xgb-v1', 82.50, JSON_OBJECT('missing_skills', JSON_ARRAY('statistics', 'tableau')));
```

The dashboard can then be updated to prefer `model_predictions` over the starter rule-based score.
