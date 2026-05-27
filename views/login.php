<?php $page_title = 'Log In – ' . APP_NAME; ?>

<div class="auth-wrap">
    <h2>Staff Login</h2>
    <form method="POST" action="<?= esc(base_url('login')) ?>" class="auth-form">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" required autofocus
                   value="<?= esc($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-full">Log In</button>
    </form>

    <details class="demo-creds">
        <summary>Demo credentials</summary>
        <table>
            <tr><th>Role</th><th>Email</th><th>Password</th></tr>
            <tr><td>Editor</td><td>editor@aatimes.test</td><td>Editor#2026</td></tr>
            <tr><td>Journalist</td><td>sagar@aatimes.test</td><td>Journo#2026</td></tr>
            <tr><td>Journalist</td><td>mahmud@aatimes.test</td><td>Journo#2026</td></tr>
            <tr><td>Journalist</td><td>subodh@aatimes.test</td><td>Journo#2026</td></tr>
        </table>
    </details>
</div>
