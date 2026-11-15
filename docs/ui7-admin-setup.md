# UI7 administrator setup

## Database prerequisite

The approved local database is `task_board_db`. Back up and verify it before
changing its schema. The approved change is:

```sql
ALTER TABLE users
ADD COLUMN role ENUM('student', 'admin')
NOT NULL DEFAULT 'student';
```

Existing users become students. Public signup explicitly inserts `student`;
it never reads a submitted role. No account is promoted automatically.

To undo this schema change, first revert the role-dependent application code,
then (with separate approval) run:

```sql
ALTER TABLE users DROP COLUMN role;
```

This removes role assignments. MariaDB schema changes commit automatically.
Restoring a backup instead would overwrite changes made since that backup.

## Provisioning requires separate approval

Do not run the creation command until the owner approves creating the account.
From an interactive Windows terminal in the project directory:

```powershell
C:\xampp\php\php.exe scripts/create_admin.php --create
```

The script prompts for a new name and email, then reads the password twice
without echoing keystrokes. Password entry requires an interactive console and
PowerShell. Do not redirect input, record the terminal, or put passwords in
commands, environment variables, source files or Git. The password travels only
through a private child-process pipe and is hashed using `password_hash()`;
only the hash is stored in the database. The script accepts 8 to 72 password
bytes to avoid bcrypt truncation.

The script refuses an existing email and never updates or promotes accounts.
It rejects web requests and does nothing without the `--create` argument.
Running without that argument only displays usage. It does not enforce a
single-admin policy; run it once for the approved dedicated account.

## Access and verification

- Administrators use the existing login page and CSRF-protected logout form.
- Successful login stores the database role and regenerates the session ID.
  Students return to the homepage; administrators go to `admin_dashboard.php`.
- Every admin handler must call `requireAdministrator()` before rendering
  content. The guard queries the current role by session user ID; it does not
  trust the session role, posted roles or navigation visibility.
- Guests redirect to login. Students, deleted accounts and invalid user IDs
  receive HTTP 403. Database failures return HTTP 503 with a generic message.
- The session role controls navigation only. Existing sessions without a role
  can sign in again; the guard still checks the current database role directly.
- Management sections are planned UI8 placeholders with disabled actions.
  Existing task/application ownership and CRUD checks are unchanged.

Before provisioning, test authentication and authorization using isolated
fixtures. After separately approved provisioning, check admin login, dashboard
links, mobile layout and logout; verify student direct access returns 403 and
guest direct access redirects. Do not submit CRUD operations for this review.

Pending final test: populated Browse Tasks keyboard scrolling.
