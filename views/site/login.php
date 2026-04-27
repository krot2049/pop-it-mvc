<h2>Вход в систему</h2>
<h3><?= $message ?? ''; ?></h3>
<?php if (!app()->auth::check()): ?>
<form method="post">
    <input name="csrf_token" type="hidden" value="<?= app()->auth::generateCSRF() ?>"/>
    <label>Логин <input type="text" name="login"></label><br>
    <label>Пароль <input type="password" name="password"></label><br>
    <button>Войти</button>
</form>
<?php endif; ?>