
# Student Task Board — Codex Instructions

## Project
This is an individual MIT4301 Independent Study project.

Read the documents in `docs/` before making development decisions. Follow the project proposal, student guidelines, and evaluation criteria.

## Architecture
- Use a lightweight layered MVC monolith with plain PHP.
- Do not use PHP frameworks.
- Use one front controller and simple routing.
- Separate Controllers, Models, and Views.
- Add a small TaskWorkflowService only when needed for complex task/application operations.
- Use the existing MySQLi database connection approach.
- Keep implementation simple and suitable for this project's size.

## Coding Style
- Write simple, readable, beginner-friendly PHP.
- Prefer clear variable names and straightforward logic.
- Avoid unnecessary abstractions, advanced design patterns, and complicated code.
- Use comments where they genuinely help explain the code.
- Keep security and correctness intact.
- Do not introduce dependencies unless approved.

## Development Process
- Work on one small feature or change at a time.
- Explain what each change does and why it is needed.
- Preserve existing functionality.
- Do not modify unrelated files.
- Ask for approval before major technical or architectural decisions.
- Do not automatically commit or push changes.
- Do not modify the database schema without approval.
- Provide simple instructions to test each change.
- Report which files were changed and why.

## Learning and Assessment
The student must be able to explain, modify, and debug all submitted code independently.

Do not produce large amounts of unexplained code. Support step-by-step learning and understanding.

## Safety
- Never commit passwords, credentials, or local configuration secrets.
- Use prepared SQL statements.
- Validate input and check authorization on the server.
- Protect state-changing requests against CSRF.
- Do not remove existing security protections.
