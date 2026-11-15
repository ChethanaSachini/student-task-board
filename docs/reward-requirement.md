# Mandatory monetary task rewards

## Requirement change

Approved on 16 November 2026: every task must offer a monetary reward greater
than LKR 0.00. This replaces the optional-reward wording in the original project
proposal. Payments remain outside the system; this change adds no payment processing.

## Validation

- Post Task and editable reward fields require an amount from LKR 0.01 to
  LKR 99,999,999.99, with at most two decimal places.
- Browser inputs use `required`, `min="0.01"`, `max="99999999.99"` and `step="0.01"`.
- Server-side validation is authoritative. Missing, blank, whitespace-only,
  zero, negative, malformed, excessive-precision and out-of-range rewards fail.
- Submitted text is trimmed. Decimal strings are normalized without float
  conversion: for example, `1` becomes `1.00` and `1.2` becomes `1.20`.
- The normalizer retains blank/zero representations for exact locked-field
  comparisons. The shared task validator rejects those representations for saving.

## Existing locks and permissions

Application, authentication, ownership and CSRF checks remain unchanged.
When applications exist, reward and deadline stay disabled. The edit controller
checks posted terms against the locked task and uses stored values when disabled
fields are omitted. Title, description and category remain editable by the owner.
No lock is bypassed to correct legacy rewards.

## Approved legacy corrections

| Task ID | Original reward | Approved reward |
|---|---|---|
| 4 | NULL | 150.00 |
| 5 | NULL | 250.00 |
| 7 | NULL | 500.00 |
| 8 | 0.00 | 750.00 |

Tasks 3 and 6 must remain unchanged. Task 6 has an application and its valid
reward remains locked. Preserve all accounts, applications and relationships.

The four approved corrections were applied in one transaction and verified
before and after commit. Only those reward values changed; all task timestamps,
other fields, accounts and applications were preserved. Counts remain five
users, six tasks and one application. The separately approved database constraints
below were then applied and verified without changing retained records.

Correction procedure:

1. Create and verify a fresh backup outside the repository.
2. Begin one transaction. Lock affected task rows in ID order using `FOR UPDATE`,
   matching the task-first locking order used by application creation.
3. Compare the complete database snapshot with the verified baseline. Check
   current application rows with a locking read, not an older consistent snapshot.
   Stop and roll back on any difference or application on an affected task.
4. Use prepared updates restricted to the four approved IDs and amounts.
   Explicitly retain `updated_at` using `updated_at = updated_at` so the automatic
   timestamp does not change. No other task field may change.
5. Compare complete before/after records against a copy containing only the four
   approved reward replacements, then commit. Verify again after commit.

## Database constraints

After confirming all records were positive and receiving separate schema approval,
the following change was applied:

```sql
ALTER TABLE tasks
MODIFY COLUMN reward DECIMAL(10,2) NOT NULL,
ADD CONSTRAINT chk_tasks_reward_positive CHECK (reward > 0);
```

Both application validation and the database now enforce the new requirement.
CHECK enforcement was confirmed before the ALTER. A connection-local temporary
copy of the resulting task definition rejected NULL, zero and negative rewards
for both INSERT and UPDATE, and accepted valid positive boundaries. The temporary
copy was removed; retained records were never mutation targets for these tests.
MariaDB DDL commits automatically and was separate from the correction transaction.
Do not restore a backup or reverse corrections without approval, especially if
applications have since been submitted.

## Focused checks

- Accept `0.01`, `1`, `1.2`, `500.00`, `99999999.99` and positive leading-zero forms.
- Reject omitted rewards, blanks, `0`, `0.00`, `000.00`, negative amounts, letters,
  exponent notation, comma-separated numbers, more than two decimal places,
  values above the maximum and array input.
- Check create and unlocked edit rejection with browser validation bypassed.
- Check locked edits with omitted terms and numerically equivalent posted terms;
  reject changed or invalid posted terms. Preserve deadline and reward locks.
- Check ownership, CSRF, application creation/edit/withdrawal, errors and escaping
  with isolated fixtures; do not mutate live records for these automated checks.
- Verify all live task rewards are positive and only the approved amounts changed,
  including preservation of timestamps and the retained application.
- Review Post/Edit Task, reward displays and the locked poster task in one short
  browser checklist without saving further live changes.

Earlier optional/zero-reward fixture expectations and demonstration instructions
are superseded. Missing-reward display fallbacks remain defensive handling for
legacy data; they do not permit saving a blank reward.
