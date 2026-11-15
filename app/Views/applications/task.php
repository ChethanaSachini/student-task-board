<?php
$pageTitle = "Task Applicants | Student Task Board";
$activePage = "my_tasks";
require __DIR__ . "/../partials/header.php";
?>

    <h1>Task Applicants</h1>

    <?php if ($message !== ""): ?>
        <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php else: ?>
        <h2><?= htmlspecialchars($task["title"], ENT_QUOTES, "UTF-8") ?></h2>

        <p class="notice" id="workflow-preview-notice">UI preview only. Selection and status updates are not implemented; nothing is saved. The applicant list below shows real applications, but no applicant can be selected here yet.</p>
        <nav class="form-actions" aria-label="Workflow previews">
            <a href="#applicant-selection-preview">1. Applicant selection</a>
            <a href="#selection-confirmation-preview">2. Selection confirmation</a>
            <a href="#selected-applicant-preview">3. Selected applicant</a>
            <a href="#task-status-preview">4. Task status</a>
            <a href="#completion-confirmation-preview">5. Completion confirmation</a>
        </nav>

        <!-- Match the existing desktop header clearance without changing shared CSS. -->
        <section id="applicant-selection-preview" aria-labelledby="applicant-selection-title" style="scroll-margin-top: 12rem;">
        <h2 id="applicant-selection-title">1. Applicant selection (preview)</h2>
        <?php if (empty($applications)): ?>
            <p class="notice">No students have applied for this task yet.</p>
            <p>The remaining workflow previews are available below even without applications.</p>
            <button class="button" type="button" disabled aria-describedby="workflow-preview-notice">Select Applicant - unavailable</button>
        <?php else: ?>
            <?php foreach ($applications as $application): ?>
                <div class="form-field">
                <article class="card">
                    <h3><?= htmlspecialchars($application["applicant_name"], ENT_QUOTES, "UTF-8") ?></h3>
                    <p><?= nl2br(htmlspecialchars($application["message"], ENT_QUOTES, "UTF-8")) ?></p>
                    <p>Submitted: <?= htmlspecialchars($application["created_at"], ENT_QUOTES, "UTF-8") ?></p>
                    <p>Updated: <?= htmlspecialchars($application["updated_at"], ENT_QUOTES, "UTF-8") ?></p>
                    <div class="form-actions">
                        <button class="button" type="button" disabled aria-describedby="workflow-preview-notice">Select Applicant - unavailable</button>
                    </div>
                </article>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <p><a href="#selection-confirmation-preview">Preview selection confirmation</a></p>
        </section>

        <div class="form-field">
            <section class="card" id="selection-confirmation-preview" aria-labelledby="selection-confirmation-title" style="scroll-margin-top: 12rem;">
                <h2 id="selection-confirmation-title">2. Selection confirmation (preview)</h2>
                <p id="selection-confirmation-help">When implemented, this screen will show the applicant being considered and ask you to confirm the assignment. No candidate has been chosen in this preview.</p>
                <button class="button" type="button" disabled aria-describedby="selection-confirmation-help">Confirm Selection - unavailable</button>
                <p><a href="#applicant-selection-preview">Back to applicants</a> | <a href="#selected-applicant-preview">Preview selected-applicant display</a></p>
            </section>
        </div>

        <div class="form-field">
            <section class="card" id="selected-applicant-preview" aria-labelledby="selected-applicant-title" style="scroll-margin-top: 12rem;">
                <h2 id="selected-applicant-title">3. Selected applicant (preview)</h2>
                <p>This is a layout placeholder, not a saved assignment.</p>
                <dl class="task-facts">
                    <dt>Selected applicant</dt>
                    <dd>The chosen student's name and application details will appear here when selection is implemented.</dd>
                </dl>
                <p><a href="#selection-confirmation-preview">Back to selection confirmation</a> | <a href="#task-status-preview">Preview task status</a></p>
            </section>
        </div>

        <div class="form-field">
            <section class="card" id="task-status-preview" aria-labelledby="task-status-title" style="scroll-margin-top: 12rem;">
                <h2 id="task-status-title">4. Task status (preview)</h2>
                <p id="task-status-help">These are planned status meanings, not this task's current status. Status storage and updates are not implemented.</p>
                <dl class="task-facts">
                    <dt>Open</dt>
                    <dd>A task awaiting assignment. Deadline eligibility will still apply.</dd>
                    <dt>Assigned</dt>
                    <dd>A task with one applicant selected by its owner.</dd>
                    <dt>Completed</dt>
                    <dd>A task marked as finished by its owner.</dd>
                </dl>
                <div class="form-field">
                    <label for="task-status-preview-control">Task status control (unavailable)</label>
                    <select class="form-control" id="task-status-preview-control" disabled aria-describedby="task-status-help">
                        <option value="" selected>Status updates unavailable</option>
                        <option>Open</option>
                        <option>Assigned</option>
                        <option>Completed</option>
                    </select>
                </div>
                <button class="button" type="button" disabled aria-describedby="task-status-help">Update Status - unavailable</button>
                <p><a href="#selected-applicant-preview">Back to selected-applicant preview</a> | <a href="#completion-confirmation-preview">Preview completion confirmation</a></p>
            </section>
        </div>

        <div class="form-field">
            <section class="card" id="completion-confirmation-preview" aria-labelledby="completion-confirmation-title" style="scroll-margin-top: 12rem;">
                <h2 id="completion-confirmation-title">5. Completion confirmation (preview)</h2>
                <p id="completion-confirmation-help">When implemented, this screen will ask the task owner to confirm that the task is finished. This preview cannot complete a task or change any application.</p>
                <button class="button" type="button" disabled aria-describedby="completion-confirmation-help">Confirm Completion - unavailable</button>
                <p><a href="#task-status-preview">Back to task-status preview</a> | <a href="#applicant-selection-preview">Back to applicants</a></p>
            </section>
        </div>

        <p><a href="task.php?id=<?= (int) $task["id"] ?>">Back to task details</a></p>
    <?php endif; ?>

    <p><a href="my_tasks.php">My Tasks</a></p>
<?php require __DIR__ . "/../partials/footer.php"; ?>
